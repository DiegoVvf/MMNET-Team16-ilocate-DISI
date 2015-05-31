<?php
/*
 * Post deployment script.
 *
 * Scope:
 * - Sets permissions (mainly write permissions wherever needed)
 * - Creates some directories
 * - Flushes runtime directories
 * - Runs migrations (will not on your local machine, unless you explicitly ask it to)
 */

# for each module check whether it is an application and assets and runtime
# should be considered
$modules = array(
    "common" => false,
    "api" => true ,
    "frontend" => true ,
    "console" => false
);

if ($argc < 2)
{
    echo "========================================================\n";
    echo "\nUsage:\n\n";
    echo "========================================================\n";
    echo "php " . $argv[0] . " environmentType migrations\n";
    echo "\nenvironmentType (required): can be (by default): prod, private (private is your PC)\n";
    echo "\n*note* additional ones can also be added into the environments folders.\n";
    echo "\nmigrations (optional), can be any of these values: migrate, no-migrate\n";
    echo "========================================================\n";
    exit();
}

$runningOnWindows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');

$envType = $argv[1];

$root = realpath(dirname(__FILE__)) . "/../../";

/**
 * replaces slashes by correspondent system directory separators
 * @param $path
 * @return mixed
 */
function pth($path)
{
    return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
}

/**
 * returns the executable path according to OS
 * @return string
 */
function getPhpPath()
{
    global $runningOnWindows;
    if ($runningOnWindows) return "php";
    else return '/usr/bin/php';
}

/**
 * runs a command
 * @param $command
 */
function runCommand($command)
{
    global $runningOnWindows;
    if (!$runningOnWindows)
        $command .= ' 2>&1';
    echo "Running command:\n $command ";
    $result = array();
    exec($command, $result);
    echo "\nResult is: \n";
    foreach ($result as $row) echo $row, "\n";
    echo "========================================================\n";
}

/**
 * Creates a directory if it doesn't exists
 * @param $path
 */
function createDirIfNotExists($path)
{
    if (!file_exists($path))
    {
        printLine("Creating directory: $path");
        mkdir($path);
    }

}

/**
 * Prints a nice line of text
 * @param $text
 */
function printLine($text)
{
    echo "========================================================\n";
    echo "$text \n";
    echo "========================================================\n";
}

/**
 * Remove directories recursively
 * @param $path
 */
function rmDirRecursive($path)
{
    global $runningOnWindows;
    if (!file_exists($path)) return;

    if ($runningOnWindows)
        runCommand("rd /S /Q " . $path);
    else
        runCommand("/bin/rm -rf " . $path);
}

/**
 * Create the config file from the environment specific
 * @param $env_config_file_path
 */
function create_config_file($env_config_file_path, $config_dir, $env_type) {
    $basic_name = basename($env_config_file_path, "-" . $env_type . ".php");
    $dst_file = $config_dir . $basic_name . "-env.php";
    $res = copy($env_config_file_path, $dst_file);
	echo "Copying $env_config_file_path to $dst_file\n";
    if (!$res) {
        echo "Error. Failed to copy $env_config_file_path to $dst_file\n";
        continue;
    }
    // force right permissions
    \chmod($dst_file, 0644);
}

if (!$runningOnWindows)
{
    $result = array();
    echo "Running as:";
    exec('/usr/bin/whoami 2>&1', $result);
    foreach ($result as $row) echo $row, "\n";
}

foreach (array_keys($modules) as $module)
{
    $is_module_an_application = $modules[$module];
    
    if ($is_module_an_application) {
        // Flush assets and create directory if not existing
        $assets_dir = pth($root . $module . "/www/assets");
        rmDirRecursive($assets_dir);
        createDirIfNotExists($assets_dir);
        chmod($assets_dir, 02777);

        // runtime
        $runtime_dir = pth($root . $module . "/runtime");
        createDirIfNotExists($runtime_dir);
        chmod($runtime_dir, 02777);
    }
    $config_dir = pth($root . $module . "/config/");
    // choose environment specific configs
    // by copying environments/*-<environmnetName>.php to *-env.php
    $files = glob(pth($config_dir . "environments/*-$envType".'.php'));
    foreach ($files as $orig_file)
    {
        create_config_file($orig_file, $config_dir, $envType);
    }
    
    // create empty local configs if these do no exist yet
//    if (!file_exists($config_dir . 'params-local.php'))
//    {
//        file_put_contents($config_dir . 'params-local.php', "<?php\nreturn array(\n\t'env.code' => 'private',\n);");
//        chmod($config_dir . 'params-local.php', 0644);
//    }
}

// applying migrations (for local machines is preferred to be done manually but...)
if (($envType != 'private' && !in_array('no-migrate', $argv)) || in_array('migrate', $argv))
{
	runCommand(getPhpPath() . ' \'' . $root . "yiic' migrate --interactive=0");
	if (in_array($envType, array('prod'))) // include any of environment types here at will
		runCommand(getPhpPath() . ' \'' . $root . "yiic' migrate --interactive=0 --connectionID=db");
}

echo "Done!\n";
