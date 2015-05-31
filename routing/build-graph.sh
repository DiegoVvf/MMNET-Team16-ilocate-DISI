#!/bin/sh
SRC_DIR=$(pwd)
ROUTING_DIR=$(cd ../ilocate-middleware-routing; pwd)
ROUTING_JAR=$ROUTING_DIR/target/otp-0.16.0-SNAPSHOT.jar

# Run server
java -Xmx512M -jar $ROUTING_JAR --basePath $SRC_DIR --build $SRC_DIR/graphs/trento

# after server created, check http://localhost:8080

