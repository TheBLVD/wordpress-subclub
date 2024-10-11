#!/bin/bash

# Define the source and destination paths
SOURCE_PATH="../subclub"
DEST_PATH="./.tmp"
ZIP_DIR="export"
ZIP_FILE="$ZIP_DIR/subclub.zip"

# Create the destination and zip directories if they don't exist
mkdir -p "$DEST_PATH"
mkdir -p "$ZIP_DIR"

# Copy the subclub folder to the .tmp directory
cp -r "$SOURCE_PATH" "$DEST_PATH"

# Remove the .git folder, .gitignore file, and .DS_Store file from the copied folder
rm -rf $DEST_PATH/subclub/.git
rm -rf $DEST_PATH/subclub/.??*
rm -rf $DEST_PATH/subclub/export
rm -rf $DEST_PATH/subclub/vendor
rm -rf $DEST_PATH/subclub/assets
rm -f $DEST_PATH/subclub/.gitignore
rm -f $DEST_PATH/subclub/.DS_Store
rm -f $DEST_PATH/subclub/README.md
rm -f $DEST_PATH/subclub/composer.json
rm -f $DEST_PATH/subclub/composer.lock
rm -f $DEST_PATH/subclub/phpcs.xml
rm -f $DEST_PATH/subclub/LICENSE
rm -f $DEST_PATH/subclub/export.sh

rm -f $ZIP_FILE
# Create a zip file of the copied folder
(cd "$DEST_PATH" && zip -r "../$ZIP_FILE" "subclub")

# Delete the copied folder
rm -rf "$DEST_PATH"

echo "The subclub folder has been copied, cleaned, zipped, and stored as $ZIP_FILE."