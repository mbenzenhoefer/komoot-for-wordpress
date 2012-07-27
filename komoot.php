<?php
/*
	Plugin Name: komoot for Wordpress
	Plugin URI: http://www.komoot.de/
	Description: Mit komoot for Wordpress kannst du Wanderungen, Mountainbike- oder Fahrradtouren ganz einfach  als Karte in deinen Blog einbinden.
	Author: komoot GmbH
	Version: 2.0
	Author URI: http://www.komoot.de
	License: GPLv2 or later
*/

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


//Funktion for embedding maps in posts and pages

function komoot_replace($matches) {

	//Get Path and Parameters
	preg_match_all("#komoot\.de/(.*)[\s|\]|\"|\']#isU",$matches[0], $path);
	preg_match_all("#width=[\'|\"]?([0-9]+%?)[\'|\"]?#is",$matches[0], $temp_width);
	preg_match_all("#height=[\'|\"]?([0-9]*%?)[\'|\"]?#is",$matches[0],$temp_height);
	
	//Create SRC
	$src = 'http://www.komoot.de/'.$path[1][0];
	
	//If width & hight is not set use default
	if ($temp_width[1][0]==0){
		$width = get_option('kmt_mapiframe_width', '100%');
	}
	else {
		$width = $temp_width[1][0];
	}
	if ($temp_height[1][0]==0){
		$height = get_option('kmt_mapiframe_height', '450');
	}
	else {
		$height = $temp_height[1][0];
	}
	
	//Return iFrame
	return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$src.'?embed&utm_campaign=wpplugin"><a href="'.$src.'?utm_campaign=wpplugin">Tour anzeigen</a></iframe>';	
}

function komoot_iframe_parser($content) {
	return preg_replace_callback("#\[.*komoot\.de/.*\]#isU", 'komoot_replace', $content);
}

if( !is_feed()){
	add_filter('the_content', 'komoot_iframe_parser');
}

// Admin Page

add_action('admin_menu', 'komoot_plugin_menu');

function komoot_plugin_menu() {
	add_options_page('komoot', 'komoot', 'manage_options', __FILE__, 'komoot_plugin_options');
}

function komoot_plugin_options() {
	if (isset($_POST['info_update']))
    {
		update_option('kmt_mapiframe_width', (string)$_POST["kmt_mapiframe_width"]);
		update_option('kmt_mapiframe_height', (string)$_POST["kmt_mapiframe_height"]);
		echo '<div class="updated fade"><p><strong>Einstellungen gespeichert</strong></p></div>';
	} else

	?>

	<div class="wrap">
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<input type="hidden" name="info_update" id="info_update" value="true" />
	<div id="icon-options-general" class="icon32"><br></div><h2>Einstellungen &rsaquo; komoot for Wordpress</h2>
		<p>Mit komoot for Wordpress kannst du Wanderungen, Mountainbike- oder Fahrradtouren ganz einfach als Karte in deinen Blog einbinden.</p>

		<h2>So funktioniert's:</h2>
		
		<p>Du kannst jede Tour einbinden, die du mit <a target="_blank" href="http://www.komoot.de/?utm_campaign=wpplugin">www.komoot.de</a> geplant hast, mit der <a target="_blank" href="http://www.komoot.de/about/mobile.html?utm_campaign=wpplugin">komoot iPhone oder Android App</a> aufgezeichnet hast oder als GPX-Datei auf <a target="_blank" href="http://www.komoot.de/login/?utm_campaign=wpplugin">Mein komoot</a> hochgeladen hast.</p>

		<h3>Einbinden einer Tour</h3>
		<p>Kopier einfach den Link zur Tour in deinen Post und setz in in eckige Klammern:</p>
		<code>[http://www.komoot.de/tour/tour-name/ID]</code>
		<p>Falls die Tour nicht erscheint, überprüfe auf der Tourseite, ob du sie <strong>öffentlich</strong> geschaltet hast.</p>
		
		<h3>Einbinden einer Übersichtskarte aller Touren:</h3>
		<p>Kopier einfach den Link zu deiner Seite bei komoot in deinen Post und setz ihn in eckige Klammern:</p>
		<code>[http://www.komoot.de/user/ID]</code>

		<h3>Höhe und Breite der Karte angeben</h3>
		<p>So kannst du aber auch jede einzelne Karte anpassen:</p> 
		<code>[http://www.komoot.de/user/ID width=80% height=400 ]<code>
		<p>Gibst du keine Größe an wird die Standard-Größe verwendet:</p>
		
		<h2>Einstellungen</h2>
		<div style="margin:20px;">
			<table class="form-table">
				<tr>
					<td style="width:250px!important;"><label>Standard-Breite der Karte</label></td>
					<td style="width:350px!important;"><input name="kmt_mapiframe_width" value="<?php echo get_option('kmt_mapiframe_width'); ?>" ></td>
					<td></td>
				</tr>
				<tr>
					<td><label>Standard-Höhe der Karte</label></td>
					<td><input name="kmt_mapiframe_height" value="<?php echo get_option('kmt_mapiframe_height'); ?>"></td>
					<td></td>
				</tr>

				<tr>
					<td colspan="2" align="center"><input type="submit" name="info_update" class="button-primary" value="Speichern" /></td>
				</tr>
			</table>
		</div>
	</form>
	</div>
	<?php
}
