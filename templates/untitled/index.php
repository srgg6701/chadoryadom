<?php
defined('_JEXEC') or die;

/**
 * Template for Joomla! CMS, created with Artisteer.
 * See readme.txt for more details on how to use the template.
 */



require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.php';

// Create alias for $this object reference:
$document = & $this;

// Shortcut for template base url:
$templateUrl = $document->baseurl . '/templates/' . $document->template;

// Initialize $view:
$view = $this->artx = new ArtxPage($this);

// Decorate component with Artisteer style:
$view->componentWrapper();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $document->language; ?>" lang="<?php echo $document->language; ?>" dir="ltr">
<head>
 <jdoc:include type="head" />
 <link rel="stylesheet" href="<?php echo $document->baseurl; ?>/templates/system/css/system.css" type="text/css" />
 <link rel="stylesheet" href="<?php echo $document->baseurl; ?>/templates/system/css/general.css" type="text/css" />
 <link rel="stylesheet" type="text/css" href="<?php echo $templateUrl; ?>/css/template.css" media="screen" />
 <!--[if IE 6]><link rel="stylesheet" href="<?php echo $templateUrl; ?>/css/template.ie6.css" type="text/css" media="screen" /><![endif]-->
 <!--[if IE 7]><link rel="stylesheet" href="<?php echo $templateUrl; ?>/css/template.ie7.css" type="text/css" media="screen" /><![endif]-->
 <script type="text/javascript">if ('undefined' != typeof jQuery) document._artxJQueryBackup = jQuery;</script>
 
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>  
 
 
<script type="text/javascript" src="<?php echo $templateUrl; ?>/script.js"></script>
 <script type="text/javascript">if (document._artxJQueryBackup) jQuery = document._artxJQueryBackup;</script>
</head>
<body>

<div id="page-background-glare-wrapper">
    <div id="page-background-glare"></div>
</div>

<div id="main">
    <div class="cleared reset-box"></div>
<? 	if (!(JRequest::getVar('option')=="com_kunena"
		  && JRequest::getVar('hide')
		 )
	   ):?>    
<div class="header">
    <div class="header-position">
        <div class="header-wrapper">
            <div class="cleared reset-box"></div>
            <div class="header-inner">
    <div class="logo">
</div>

        </div>
    </div>
</div>
</div>
<?	else:?>
<style>
div.box.sheet{
	margin-top:10px;
}
</style>
<?	endif;?>
<div class="header1">
    <div class="header1-position">
        <div class="header1-wrapper">
            <div class="cleared reset-box"></div>
            <div class="header1-inner">
            </div>
        </div>
    </div>
</div>

<div class="header2">
    <div class="header2-position">
        <div class="header2-wrapper">
            <div class="cleared reset-box"></div>
            <div class="header2-inner">
        <?php echo $view->position('header'); ?>
            </div>
        </div>
    </div>
</div>

<div class="header3">
    <div class="header3-position">
        <div class="header3-wrapper">
            <div class="cleared reset-box"></div>
            <div class="header3-inner">
        <?php echo $view->position('header2'); ?>
            </div>
    
        </div>
    </div>
</div>

<div class="cleared reset-box"></div>

<div class="box sheet">
    <div class="box-body sheet-body">
<?php if ($view->containsModules('user3', 'extra1', 'extra2')) : ?>
        <div class="bar nav">
            <div class="nav-outer">
                <?php if ($view->containsModules('extra1')) : ?>
                <div class="hmenu-extra1"><?php echo $view->position('extra1'); ?></div>
                <?php endif; ?>
                <?php if ($view->containsModules('extra2')) : ?>
                <div class="hmenu-extra2"><?php echo $view->position('extra2'); ?></div>
                <?php endif; ?>
                <?php echo $view->position('user3'); ?>
            </div>
        </div>
        <div class="cleared reset-box"></div>
    <?php endif; ?>
    <?php echo $view->position('banner1', 'nostyle'); ?>
    <?php echo $view->positions(array('top1' => 33, 'top2' => 33, 'top3' => 34), 'block'); ?>
        <div class="layout-wrapper">
            <div class="content-layout">
                <div class="content-layout-row">
        <?php if ($view->containsModules('left')) : ?>
        <div class="layout-cell sidebar1">
        <?php echo $view->position('left', 'block'); ?>
        
          <div class="cleared"></div>
        </div>
        <?php endif; ?>
        <div class="layout-cell content">
        
        <?php
          echo $view->position('banner2', 'nostyle');
          if ($view->containsModules('breadcrumb'))
            echo artxPost($view->position('breadcrumb'));
          echo $view->positions(array('user1' => 50, 'user2' => 50), 'article');
          echo $view->position('banner3', 'nostyle');
          if ($view->hasMessages())
            echo artxPost('<jdoc:include type="message" />');
          echo '<jdoc:include type="component" />';
          echo $view->position('banner4', 'nostyle');
          echo $view->positions(array('user4' => 50, 'user5' => 50), 'article');
          echo $view->position('banner5', 'nostyle');
        ?>
        
          <div class="cleared"></div>
        </div>
        <?php if ($view->containsModules('right')) : ?>
        <div class="layout-cell sidebar2">
        <?php echo $view->position('right', 'block'); ?>
        
          <div class="cleared"></div>
        </div>
        <?php endif; ?>
        
                </div>
            </div>
        </div>
        <div class="cleared"></div>

    <?php echo $view->position('banner6', 'nostyle'); ?>
        <div class="footer">
            <div class="footer-body">
                <?php echo $view->position('syndicate'); ?>
                        <div class="footer-text">
                           <?php echo $view->position('footer', 'nostyle'); ?>
                        </div>
        <?php echo $view->positions(array('bottom1' => 33, 'bottom2' => 33, 'bottom3' => 34), 'nostyle'); ?>
                <div class="cleared"></div>
            </div>
        </div>
        
        <div class="cleared"></div>

    </div>
</div>

<div class="cleared"></div>
<p class="page-footer"></p>

    <div class="cleared"></div>

</div>

<?php echo $view->position('debug'); ?>
</body>
</html>
