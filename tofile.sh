#!/bin/bash

# Save lines to a file. Always adds. Just a test script.
# Useful if you are playing with pipes and scripts

#echo "Params: " $0 $1 $2 $texto >> /tmp/tofile

while read line; do
	echo "$line" >> /tmp/tofile
done

