<script type="text/javascript">
    tinyMCE.init({
        theme : "advanced",
        mode : "textareas",
		language : 'fr',
        convert_urls : false,
		editor_selector : 'mceAdvanced',
		plugins : "safari,spellchecker,advhr,advimage,advlink,table,emotions,inlinepopups,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager",

	// Theme options
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,link,unlink,|,charmap,forecolor,backcolor,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "newdocument,undo,redo,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,cleanup,code,preview",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true
});
</script>

<table class="toolbar">
	<tr>
    	<td><h1>Ajouter un festival</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'festivals', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    <?php 
	
	echo ($form->create('Festival', array('action' => 'add', 'type' => 'file')));
     
	echo $form->input('name', array('label' => 'Nom :'));
	echo $form->input('url', array('type' => 'hidden'));
	echo $form->input('country_id', array('label' => 'Pays :'));
	
	$ajout = array('0' => '-- Choisir --');
	$regions = $ajout + $regions;
	$departments = $ajout + $departments;
	//$cities = $ajout + $cities;
	echo $form->input('region_id', array('label' => 'Région :<br /><span class="notes">(facultatif)</span>', 'options' => $regions));
	echo $form->input('department_id', array('label' => 'Département :<br /><span class="notes">(facultatif)</span>', 'options' => $departments));
	echo $form->input('postal_code', array('label' => 'Code Postal :'));
	//echo $form->input('city_id', array('label' => 'Ville :<br /><span class="notes">(facultatif)</span>', 'options' => $cities));
	
	echo $form->input('Genre', array('label' => 'Genre(s) :', 'multiple' => 'true'));
	
	echo $form->input('capacity', array('label' => 'Places max. :', 'default' => 0));
	echo $form->input('creation_year', array('label' => 'Année de création :', 'default' => 0));
	echo $form->input('website', array('label' => 'Site web :<br /><span class="notes">(sans http://)</span>'));
	echo $form->input('email', array('label' => 'Contact :<br /><span class="notes">(email)</span>'));
	
	echo $form->input('photo_c', array('type' => 'file', 'label' => 'Photo cover :<br /><span class="notes">(dimension 938x170, jpg ou png)</span>'));
	echo $form->input('photo_r', array('type' => 'file', 'label' => 'Profile pic :<br /><span class="notes">(100x100)</span>'));
	echo $form->input('affiche', array('type' => 'file', 'label' => 'Affiche promo :<br /><span class="notes">(format portrait, > 300px largeur)</span>'));
	 ?> 
     
    <fieldset>
        <legend>Biographies</legend>
        <?php
        $descriptions = Set::combine($this->data['Biographies'], '{n}.locale', '{n}.content');
     
        foreach(Configure::read('Config.languages') as $codeLang => $locale):
            e("<div>Biographie ($codeLang) :<br /><br />");
            e($form->textarea(
                'Festival.bio.'.$locale,
                array(
					'class' => 'mceAdvanced',
                    'value' => isset($descriptions[$locale]) ? $descriptions[$locale] : ''
                )
            ));
            e('</div>');
        endforeach;
        ?> 
    </fieldset>
     
    <?php e($form->end('Valider')); ?>
