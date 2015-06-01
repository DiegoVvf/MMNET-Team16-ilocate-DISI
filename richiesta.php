{\rtf1\ansi\ansicpg1252\cocoartf1344\cocoasubrtf720
{\fonttbl\f0\fnil\fcharset0 Monaco;}
{\colortbl;\red255\green255\blue255;\red0\green0\blue173;\red239\green244\blue246;\red13\green103\blue1;
\red210\green0\blue5;}
\paperw11900\paperh16840\margl1440\margr1440\vieww10800\viewh8400\viewkind0
\deftab720
\pard\pardeftab720

\f0\fs20 \cf2 \cb3 \expnd0\expndtw0\kerning0
<?php\cb1 \expnd0\expndtw0\kerning0
\
\cb3 \expnd0\expndtw0\kerning0
mysql_connect\cf4 \expnd0\expndtw0\kerning0
(\cf5 \expnd0\expndtw0\kerning0
"localhost:3306"\cf4 \expnd0\expndtw0\kerning0
,\cf5 \expnd0\expndtw0\kerning0
"\'a0root\'a0"\cf4 \expnd0\expndtw0\kerning0
,\cf5 \expnd0\expndtw0\kerning0
""\cf4 \expnd0\expndtw0\kerning0
);\cb1 \expnd0\expndtw0\kerning0
\
\cf2 \cb3 \expnd0\expndtw0\kerning0
mysql_select_db\cf4 \expnd0\expndtw0\kerning0
(\cf5 \expnd0\expndtw0\kerning0
\'93qr_db\'94\cf4 \expnd0\expndtw0\kerning0
);\cb1 \expnd0\expndtw0\kerning0
\
\cb3 \expnd0\expndtw0\kerning0
\'a0\cb1 \expnd0\expndtw0\kerning0
\
\cf2 \cb3 \expnd0\expndtw0\kerning0
$q\cf4 \expnd0\expndtw0\kerning0
=\cf2 \expnd0\expndtw0\kerning0
mysql_query\cf4 \expnd0\expndtw0\kerning0
(\cf5 \expnd0\expndtw0\kerning0
"SELECT\'a0*\'a0FROM\'a0codici\'a0WHERE\'a0id="\cf4 \expnd0\expndtw0\kerning0
.\cf2 \expnd0\expndtw0\kerning0
$_REQUEST\cf4 \expnd0\expndtw0\kerning0
[\cf5 \expnd0\expndtw0\kerning0
'idnomerichiesto'\cf4 \expnd0\expndtw0\kerning0
]);\cb1 \expnd0\expndtw0\kerning0
\
\cb3 \expnd0\expndtw0\kerning0
while(\cf2 \expnd0\expndtw0\kerning0
$e\cf4 \expnd0\expndtw0\kerning0
=\cf2 \expnd0\expndtw0\kerning0
mysql_fetch_assoc\cf4 \expnd0\expndtw0\kerning0
(\cf2 \expnd0\expndtw0\kerning0
$q\cf4 \expnd0\expndtw0\kerning0
))\cb1 \expnd0\expndtw0\kerning0
\
\cb3 \expnd0\expndtw0\kerning0
\'a0\'a0\'a0\'a0\'a0\'a0\'a0\'a0\cf2 \expnd0\expndtw0\kerning0
$output\cf4 \expnd0\expndtw0\kerning0
[]=\cf2 \expnd0\expndtw0\kerning0
$e\cf4 \expnd0\expndtw0\kerning0
;\cb1 \expnd0\expndtw0\kerning0
\
\cb3 \expnd0\expndtw0\kerning0
\'a0\cb1 \expnd0\expndtw0\kerning0
\
\cb3 \expnd0\expndtw0\kerning0
print(\cf2 \expnd0\expndtw0\kerning0
json_encode\cf4 \expnd0\expndtw0\kerning0
(\cf2 \expnd0\expndtw0\kerning0
$output\cf4 \expnd0\expndtw0\kerning0
));\cb1 \expnd0\expndtw0\kerning0
\
\cb3 \expnd0\expndtw0\kerning0
\'a0\cb1 \expnd0\expndtw0\kerning0
\
\cf2 \cb3 \expnd0\expndtw0\kerning0
mysql_close\cf4 \expnd0\expndtw0\kerning0
();\cb1 \expnd0\expndtw0\kerning0
\
\cf2 \cb3 \expnd0\expndtw0\kerning0
?>}