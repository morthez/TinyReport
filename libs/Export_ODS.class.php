<?php
/**
* Export_ODS is a class to export to the openformat
* ODS used by OpenOffice and others.
* 
* Author: Marius Davidsen
*/
define ("TYPE_DEFAULT", 1);
define ("TYPE_PERCENT", 1<<1);
define ("TYPE_FLOAT", 1<<2);
Class Export_ODS{
	
	/*
	* content_xml contains the output of the file
	* /content.xml.  
	*/
	public $content_xml;
	
	/*
	* Styles.xml content.
	* Not in use if using default styles.
	*/
	var $styles_xml;
	
	/*
	* 
	*/
	var $encoding;
	
	/**
	* function desc
	*/
	function __construct ( $encoding ) {
		$this->content_xml .='<?xml version="1.0" encoding="'. $encoding .'"?>';
		$this->content_xml .= '
<office:document-content
		xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"
		xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0"
		xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0"
		xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0"
		xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0"
		xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0"
		xmlns:xlink="http://www.w3.org/1999/xlink"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0"
		xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0"
		xmlns:presentation="urn:oasis:names:tc:opendocument:xmlns:presentation:1.0"
		xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0"
		xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0"
		xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0"
		xmlns:math="http://www.w3.org/1998/Math/MathML"
		xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0"
		xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0"
		xmlns:ooo="http://openoffice.org/2004/office"
		xmlns:ooow="http://openoffice.org/2004/writer"
		xmlns:oooc="http://openoffice.org/2004/calc"
		xmlns:dom="http://www.w3.org/2001/xml-events"
		xmlns:xforms="http://www.w3.org/2002/xforms"
		xmlns:xsd="http://www.w3.org/2001/XMLSchema"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xmlns:rpt="http://openoffice.org/2005/report"
		xmlns:of="urn:oasis:names:tc:opendocument:xmlns:of:1.2"
		xmlns:xhtml="http://www.w3.org/1999/xhtml"
		xmlns:grddl="http://www.w3.org/2003/g/data-view#"
		xmlns:field="urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0" 
		office:version="1.2" 
		grddl:transformation="http://docs.oasis-open.org/office/1.2/xslt/odf2rdf.xsl"> 
		<office:font-face-decls> 
			<style:font-face 
			style:name="Arial" 
			svg:font-family="Arial"
			style:font-family-generic="swiss"
			style:font-pitch="variable" /> <style:font-face
			style:name="Arial Unicode MS" svg:font-family="&apos;Arial Unicode MS&apos;"
			style:font-family-generic="system"
			style:font-pitch="variable"/> 
			<style:font-face
			style:name="SimSun" svg:font-family="SimSun"
			style:font-family-generic="system"
			style:font-pitch="variable"/>
			<style:font-face
			style:name="Tahoma" svg:font-family="Tahoma"
			style:font-family-generic="system"
			style:font-pitch="variable"/> 
		</office:font-face-decls>';
	}
	
	/**
	* Generate XML from a two dimensional array. 
	* 
	* @Param array $data two dimensional array Row => Cell
	* @param string $worksheet_name Tittle of worksheet
	* TODO:
	*/
	function generate_xml( $data, $worksheet_name ) {
		//$data = parse_numeric($indata);
		$this->content_xml .='
<office:body>
	<office:spreadsheet>
		<table:table table:name="'. $worksheet_name .'" table:print="false">';
		
		foreach ($data as $row) {
			$this->content_xml .= '
			<table:table-row table:style-name="ro1">';
			foreach ($row as $key=>$cell) {
				if ( preg_match('/^[-+]?[0-9]\d{0,4}(\.\d{0,2})%$/', $row[$key] ) ) {
					$type = TYPE_PERCENT;
				} else if ( is_int ( $cell ) || is_float ( $cell ) ||  is_numeric ( $cell ) ) {
					$type = TYPE_FLOAT;
				} else if ( is_string ( $cell ) ) {
					$type = TYPE_STRING;
				} else {
					throw new exception("Unable to recognize $cell as a known format.\n");
				}
				switch ( $type ) {
					case TYPE_STRING:
						$this->content_xml .= ('
				<table:table-cell table:style-name="Default" office:value-type="string">	');
						$this->content_xml .= '
					<text:p>'. $cell .'</text:p>
				</table:table-cell>';
					break;
					
					case TYPE_PERCENT:
						$this->content_xml .= '<table:table-cell office:value-type="percentage" office:value="'. ($cell/100) .'">';
						$this->content_xml .= '
					<text:p>'. $cell .'</text:p>
				</table:table-cell>';
					break;
					
					case TYPE_FLOAT:
						$this->content_xml .= '<table:table-cell office:value-type="float" office:value="'. $cell .'">';
						$this->content_xml .= ('
					<text:p>'. $cell .'</text:p>
				</table:table-cell>');
					break;
				}
			}
			$this->content_xml .= '
			</table:table-row>';
		}
		$this->content_xml .= '
		</table:table>
	</office:spreadsheet>
</office:body>
</office:document-content>
';	}

	
	/**
	* function desc
	*/
	function compress($filename, $content_xml) {
		$temp_name = time();
		mkdir(SPREADSHEET_TEMP_FOLDER_BASE ."\\". $temp_name, 0777); // the mode parameter is ignored on windows.
		
		if (!is_file(ZIP_UTIL)) {
			throw new exception('Unable to locate the compression utility. Please check configuration.');
		}
		if (!is_dir(SPREADSHEET_DEFAULT_TEMPLATE_PATH)){
			throw new exception('Default spreadsheet template path does not exsist. Please check configuration and or folder permissions.\n');
		}
		if (!is_dir(SPREADSHEET_TEMP_FOLDER_BASE)){
			throw new exception('Unable to open/locate temp folder for spreadsheet generation. Please check configuration and or folder permissions.\n');
		}
		if (is_writable(SPREADSHEET_TEMP_FOLDER_BASE)) {
			if (!$handle = fopen(SPREADSHEET_TEMP_FOLDER_BASE ."\\". $temp_name ."\\".$filename, 'w+')) {
				throw new exception('Unable to open: '. SPREADSHEET_TEMP_FOLDER_BASE ."\\". $temp_name ."\\".$filename .'. Please check folder permissions.\n');
				exit;
			}
			if (!fwrite($handle, $content_xml)) {
				throw new exception('Unable to write content to ' . $filename . '. Please check folder permissions.\n');
			}
			
			//every thing looks fine, so let's close the file now.
			fclose($handle);
		} else {
			throw new exception('The file '. $filename .' is not writeable. Please check configuration and or folder permissions.\n ');
		}
		$test = exec ( "\"". ZIP_UTIL ."\" ". ZIP_ARGS ." \"". SPREADSHEET_TEMP_FOLDER_BASE ."\\". $temp_name ."\\". $temp_name .".ods\" \"". SPREADSHEET_TEMP_FOLDER_BASE ."\\". $temp_name ."\\". $filename ."\" \"". SPREADSHEET_DEFAULT_TEMPLATE_PATH ."\"* ", $output, $return_var); 
		if (!$test) {
			print_r (  "\"". ZIP_UTIL ."\" ". ZIP_ARGS ." \"". SPREADSHEET_TEMP_FOLDER_BASE ."\\". $temp_name ."\\". $temp_name .".ods\" \"". SPREADSHEET_TEMP_FOLDER_BASE ."\\". $temp_name ."\\". $filename ."\" \"". SPREADSHEET_DEFAULT_TEMPLATE_PATH ."\"* ");
			echo "<pre> \$test: ";
			var_dump($test);
			echo "\$output: ";
			var_dump($output);
			echo "\$return_var: ";
			var_dump($return_var);
			throw new exception('Could not prossess the compression. Please check configuration and or folder permissions.\n ');
		}
		$this->serve($temp_name, $temp_name);
	}
	
	/**
	* function desc
	*/
	function serve($path, $file) {
		$filename = ($path ."/". $file .".ods");
		header("Content-Description: File Transfer");
		header("Cache-Control: public"); 
		header("Content-Transfer-Encoding: binary");
		header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
		header('Content-Disposition: attachment; filename=temp/'.$path.'/'.$file.'.ods');
		readfile('temp/'.$path.'/'.$file.'.ods');
	}
	
	/**
	* function desc
	*/
	function save_to_disk() {
		header('Content-Type: text/xml');
		header('Content-Disposition: inline; filename="content.xml"');
		print_r($this->content_xml);
	}
}

?>