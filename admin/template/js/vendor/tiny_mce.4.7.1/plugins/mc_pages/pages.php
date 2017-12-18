<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
#
# OFFICIAL TEAM :
#
#   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
#
# Redistributions of files must retain the above copyright notice.
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# -- END LICENSE BLOCK -----------------------------------

# DISCLAIMER

# Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
# versions in the future. If you wish to customize MAGIX CMS for your
# needs please refer to http://www.magix-cms.com for more information.
*/
/**
 * Author: Gerits Aurelien <aurelien[at]magix-cms[point]com>
 * Copyright: MAGIX CMS
 * Date: 12/02/13
 * Update: 07/10/2013
 * Time: 19:13
 * License: Dual licensed under the MIT or GPL Version
 */
$baseadmin = '../../../../../../baseadmin.php';
if(file_exists($baseadmin)){
    require_once $baseadmin;
    if(!defined('PATHADMIN')){
        throw new Exception('PATHADMIN is not defined');
    }elseif(!defined('VERSION_EDITOR')){
        throw new Exception('VERSION_EDITOR is not defined');
    }
}
/**
 * Class pluginsAuth
 */
class pluginsAuth{
    public function basePath(){
        $realpathFilemanager = dirname(realpath( __FILE__ ));
        $filemanagerArrayDir = array(PATHADMIN.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'tiny_mce.'.VERSION_EDITOR.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'mc_pages');
        $filemanagerPath = str_replace($filemanagerArrayDir, array('') , $realpathFilemanager);
        return $filemanagerPath;
    }
}
$auth = new pluginsAuth();
$config_in = $auth->basePath().'/app/init/common.inc.php';

if (file_exists($config_in)) {
    require $config_in;
}else{
    throw new Exception('Error Ini Common Files');
    exit;
}
/**
 * Chargement du Bootsrap
 */
$bootstrap = $auth->basePath().'/lib/bootstrap.php';
if (file_exists($bootstrap)){
    require $bootstrap;
}else{
    throw new Exception('Boostrap is not exist');
    exit;
}

$loader = new autoloader();
$loader->addPrefixes(array(
    'component' => $auth->basePath().'/app',
    'backend' => $auth->basePath().'/app',
));
$loader->addPrefix('plugins',filter_path::basePath(array('lib','magepattern')));
$loader->register();

$current_language = backend_model_template::currentLanguage();
$modelLang = new backend_model_language(null);
$langs = $modelLang->setLanguage();
$members = new backend_controller_login();
$members->secure();
$pages = new backend_controller_pages();
$lists = array();
foreach($langs as $k => $iso) {
	$lists[$k] = $pages->getListPages($k);
}

session_write_close();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<title>search CMS pages</title>
		<link href="/<?php print PATHADMIN; ?>/template/css/src/style.min.css" rel="stylesheet">
		<!--<link href="css/mc_pages.css" rel="stylesheet">-->
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="/libjs/html5shiv.js" type="text/javascript"></script>
		<script src="/libjs/respond.min.js" type="text/javascript"></script>
		<![endif]-->
		<script type="text/javascript" src="/<?php print PATHADMIN; ?>/min/?g=publicjs,globalize,jimagine"></script>
		<script type="text/javascript" src="/<?php print PATHADMIN; ?>/min/?f=/libjs/vendor/bootstrap-select.min.js,/libjs/vendor/livefilter.min.js,/libjs/vendor/tabcomplete.min.js"></script>
		<script src="js/vendor/mustache.js"></script>
		<script type="text/javascript">var baseadmin = <?php print '"'.PATHADMIN.'"'; ?>;</script>
		<script src="js/mc_pages.js"></script>
	</head>
	<body>
	<?php function makeTree($data) {
		foreach ($data as $link) { ?>
		<li class="filter-item items" data-filter="<?php echo $link['name_pages']; ?>" data-value="<?php echo $link['id_pages']; ?>" data-id="<?php echo $link['id_pages']; ?>">
			<?php echo ucfirst($link['name_pages']);
			if (!empty($link['subdata'])) { ?>
			<li class="optgroup">
				<ul class="list-unstyled">
					<?php makeTree($link['subdata']); ?>
				</ul>
			</li>
			<?php } ?>
		</li>
	<?php } } ?>
		<div class="container">
			<div id="template-container" class="row">
				<form action="#" class="col-ph-12 col-md-6 col-lg-4 validate_form">
					<div class="form-group">
						<div class="dropdown dropdown-lang">
							<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<?php $i=0; foreach ($langs as $id => $iso) { if($i === 0) { $default = $id; break; } $i++; } ?>
								<span class="lang"><?php echo $langs[$default]; ?></span>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<?php $i=0; foreach($langs as $id => $iso) { ?>
								<li role="presentation"<?php if($i === 0) { ?> class="active"<?php } ?>>
									<a data-target="#page-lang-<?php echo $id; ?>" aria-controls="page-lang-<?php echo $id; ?>" role="tab" data-toggle="tab"><?php echo $iso; ?></a>
								</li>
								<?php $i++; } ?>
							</ul>
						</div>
					</div>
					<div class="tab-content">
						<?php $i=0; foreach($langs as $id => $iso) { ?>
						<fieldset role="tabpanel" class="tab-pane<?php if($i === 0) { ?> active<?php } ?>" id="page-lang-<?php echo $id; ?>">
							<h2>Ajouter un lien</h2>
							<div class="form-group">
								<div id="<?php echo $iso; ?>pages" class="btn-group btn-block selectpicker" data-clear="true" data-live="true">
									<a href="#" class="clear"><span class="fa fa-times"></span><span class="sr-only">Annuler la sélection</span></a>
									<button data-id="parent" type="button" class="btn btn-block btn-default dropdown-toggle">
										<span class="placeholder">Choississez un lien à ajouter</span>
										<span class="caret"></span>
									</button>
									<div class="dropdown-menu">
										<div class="live-filtering" data-clear="true" data-autocomplete="true" data-keys="true">
											<label class="sr-only" for="input-pages">Rechercher dans la liste</label>
											<div class="search-box">
												<div class="input-group">
													<span class="input-group-addon" id="search-pages">
														<span class="fa fa-search"></span>
														<a href="#" class="fa fa-times hide filter-clear"><span class="sr-only">Effacer filtre</span></a>
													</span>
													<input type="text" placeholder="Rechercher dans la liste" id="input-pages" class="form-control live-search" aria-describedby="search-pages" tabindex="1" />
												</div>
											</div>
											<div id="filter-pages" class="list-to-filter tree-display">
												<ul class="list-unstyled">
												<?php if(!empty($lists[$id])) { makeTree($lists[$id]); } ?>
												</ul>
												<div class="no-search-results">
													<div class="alert alert-warning" role="alert"><i class="fa fa-warning margin-right-sm"></i>Aucune entrée pour <strong>'<span></span>'</strong> n'a été trouvée.</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<input type="hidden" name="<?php echo $iso; ?>pages_id" id="<?php echo $iso; ?>pages_id" class="form-control pages_id" value="" data-lang="<?php echo $iso; ?>"/>
							</div>
						</fieldset>
						<?php $i++; } ?>
					</div>
				</form>
				<form id="link-data" action="#" method="post" class="col-ph-12 col-md-6 col-lg-4 validate_form collapse">
					<div class="form-group">
						<label for="text">Texte affiché</label>
						<input type="text" id="text" class="form-control required" placeholder="Texte affiché" required/>
					</div>
					<div class="form-group">
						<label for="title">Libellé du lien</label>
						<input type="text" id="title" class="form-control required" placeholder="Texte au survol" required/>
					</div>
					<div class="form-group">
						<label for="url">URL du lien</label>
						<input type="text" id="url" class="form-control required" value="" readonly required/>
					</div>
					<div class="form-group">
						<label for="blank">Ouvrir le lien dans un nouvel onglet</label>
						<div class="switch">
							<input type="checkbox" id="blank" class="switch-native-control" />
							<div class="switch-bg">
								<div class="switch-knob"></div>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-main-theme">Insérer le lien</button>
				</form>
			</div>
		</div>
	</body>
</html>