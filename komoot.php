<?php
/*
	Plugin Name: komoot for Wordpress
	Plugin URI: http://www.komoot.de/
	Description: Mit komoot for Wordpress kannst du Wanderungen, Mountainbike- oder Fahrradtouren ganz einfach  als Karte, &Uuml;bersichtskarte oder Liste in deinen Blog einbinden.
	Author: komoot GmbH
	Version: 0.2
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

//Include the Tourlist Widget
include_once dirname( __FILE__ ) . '/komoot-tourlist-widget.php';

//Funktion for embedding maps in posts and pages
/*
	Use [ www.komoot.de/c/username/ ] for the general map of an user.
	Use [ www.komoot.de/c/username/album/tour/id ] for a map of a single tour.
	You can set default height and width in the admin panel and also in the post:
	[ www.komoot.de/c/username/album/tour/id width=600 height=400 ]
*/

function komoot_replace($matches) {

	//Get URL and Parameters
	preg_match_all("#komoot\.de/c/(.*)[\s|\]|\"|\']#isU",$matches[0], $url);
	preg_match_all("#width=[\'|\"]?([0-9]+%?)[\'|\"]?#is",$matches[0], $temp_width);
	preg_match_all("#height=[\'|\"]?([0-9]*%?)[\'|\"]?#is",$matches[0],$temp_height);
	
	//Add a '/' to general map URL of an user
	if (preg_match("#[0-9]$#", $url[1][0])==0 && preg_match("#.*\/$#", $url[1][0])==0){
		$url [1][0] = $url[1][0] . '/'; 
	}
	
	//Create SRC
	$src = 'http://www.komoot.de/c/' . $url[1][0]. '?embed';
	
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
	return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$src.'"><a title="Mountainbike Touren Fahrradtouren Wanderungen" href="'.$src.'">Klicke hier, um die Wanderung, Mountianbike Tour oder Fahrradtour auf dein iPhone, Anroid Outdoor GPS-Gerät zu laden</a></iframe>';
	
}

function komoot_iframe_parser($content) {
	return preg_replace_callback("#\[.*komoot\.de/c/.*\]#isU", 'komoot_replace', $content);
}

add_filter('the_content', 'komoot_iframe_parser');


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
		<p>Mit komoot for Wordpress kannst du Wanderungen, Mountainbike- oder Fahrradtouren ganz einfach als Karte, &Uuml;bersichtskarte oder Liste in deinen Blog einbinden.</p>

		<h2>So funktioniert's:</h2>
		<p>Du kannst jede Tour, die du auf <a target="_blank" href="http://www.komoot.de/">www.komoot.de</a> geplant hast, mit der komoot iPhone App aufgezeichnet hast oder als GPX-Datei auf <a target="_blank" href="http://www.komoot.de/c/">Mein komoot</a> hochgeladen hast. Mehr zum anelgen einer Tour erf&auml;hrst du im <a target="_blank" href="http://www.komoot.de/blog">komoot Blog</a>.

		<h3>Einbinden einer &Uuml;bersichtskarte aller Touren:</h3>
		<table>
			<tr>
				<td>
					<img style="margin-right:20px;" src="<?php echo dirname( __FILE__ );?>/example-1.jpg" />
				</td>
				<td>
					<p>F&uuml;ge den Link zu deinem Profil bei komoot wie folgt in einen Post oder eine Seite ein:</p>
					<code><strong>[ www.komoot.de/c/BENUTZERNAME/ ]</strong></code><br/>
					<i>Beispiel: [ www.komoot.de/c/komoot/ ]</i>
				</td>
			</tr>
		</table>
		
		
		<h3>Einbinden einer einzelnen Tour als Karte:</h3>
		<table>
			<tr>
				<td>
					<img style="margin-right:20px;" src="<?php echo dirname( __FILE__ );?>/example-2.jpg" />
				</td>
				<td>
					<p>F&uuml;ge den Link zu deiner Tour  wie folgt in einen Post oder eine Seite ein. Den Link für eine einzelen Tour findest du in <a target="_blank" href="http://www.komoot.de/c/">Mein komoot</a>.</p>
					<code><strong>[ www.komoot.de/c/BENUTZERNAME/ALBUM/TOUR/ID ]</strong></code><br/>
					<i>Beispiel: [ http://www.komoot.de/c/komoot/album/Ueber-die-Bruecke/74049 ]</i>
				</td>
			</tr>
		</table>
		
		<h3>Einbinden einer Tourliste als Widget in der Seitenleiste deines Blogs</h3>
		<table>
			<tr>
				<td>
					<img style="margin-right:20px;" src="<?php echo dirname( __FILE__ );?>/example-3.jpg" />
				</td>
				<td>
					<p>Aktiviere das komoot Tourlisten Widget unter <a href="plugins.php">Plugins</a>.<br/>
					Unter Design > <a href="widgets.php">Widgets</a> kannst du dann die komoot Tourliste an die von dir gew&uuml;nschte Stelle einf&uuml;gen und alle Einstellungen vornehmen.</p>
				</td>
			</tr>
		</table>
		
		
		<h2>Einstellungen</h2>
		<div style="margin:20px;">
			<table class="form-table">
				<tr>
					<td width="200"><label>Standard-Breite der Karte</label></td>
					<td width="300"><input name="kmt_mapiframe_width" value="<?php echo get_option('kmt_mapiframe_width'); ?>" ></td>
					<td rowspan=3 valign="top"><i>Hier kannst du die Standard-Gr&ouml;&szlig;e der eingebundenen Karte festlegen. So kannst du aber auch jede einzelne Karte anpassen: <code>[ www.komoot.de/c/BENUTZERNAME/ALBUM/TOUR/ID width=100% height=450 ]<code> </i></td>
				</tr>
				<tr>
					<td><label>Standard-H&ouml;he der Karte</label></td>
					<td><input name="kmt_mapiframe_height" value="<?php echo get_option('kmt_mapiframe_height'); ?>"></td>
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
