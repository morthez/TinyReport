<?php
 /**
 * Spreadsheet configuration defaults.
 * 
 */
 
 /**
 * Contents of the default_template should be the following files.
 * 
 * Important files:
 * Configurations2\accelerator\current.xml
 * content.xml -> Will be generated by the Export_ODS class.
 * META-INF\manifest.xml
 * meta.xml
 * mimetype
 * settings.xml
 * styles.xml
 * 
 * Optional files:
 * Thumbnails\thumbnail.png 
 */
 define('SPREADSHEET_DEFAULT_TEMPLATE_PATH', 'C:\\wwwroot\\templates\\spreadsheet\\default\\');
 
 /**
 * Option to export to folder.
 * The web-server service needs access to write files to this folder. 
 */
 define('SPREADSHEET_EXPORT_FOLDER_BASE', '\\\\localhost\\Users\\marius\\');
 
 /**
 * temp folder path..
 * The web-server service needs access to write files to this folder. 
 */
 define('SPREADSHEET_TEMP_FOLDER_BASE', 'C:\\wwwroot\\temp');
 
 /**
 * Compression utility. use full path. 
 * Needs ofcource to support commandline compression.
 * Supported software: 7zip
 * Possible support, aslong as you spesify the 
 * parameters equal to this:
 * <ZIP_UTIL> <ZIP_ARGS> <PATH_TO_FILE/FILENAME> <FILES TO COMPRESS>
 * 7zip Example: 
 * 7zip.exe a c:\test.ods c:\folder_to_compress\* <compresses all the files in the folder and give the file name test.ods>
 * 7zip.exe a c:\test.ods c:\folder_to_compress\file1.xml c:\folder_to_compress\file2.xml
 */
 define('ZIP_UTIL','C:\\Program Files\\7-Zip\\7z.exe');

 /**
 * Compression utility parameters for Archiving.
 * Only use needed parameters to start achiving.
 */
 define('ZIP_ARGS', 'a');


?>