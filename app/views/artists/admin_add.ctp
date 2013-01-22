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
    	<td><h1>Modifier un artiste</h1></td>
    	<td width="50" class="center">
		<?php echo $html->link($html->image('icons/cancel.png') . '<br />Annuler', array('controller' => 'artists', 'action' => 'index', 'prefix' => 'admin'), array('escape' => false)); ?>
        </td>
    </tr>
</table>
	
    <?php 
	echo ($form->create('Artist', array('action' => 'add')));
     
  echo $form->input('name', array('label' => 'Nom :'));
	echo $form->input('other_names', array('label' => 'Autres noms :<br /><span class="notes">séparés par virgule</span>'));
	echo $form->input('url', array('type' => 'hidden'));
	echo $form->input('country_id', array('label' => 'Nationalité :'));
	echo $form->input('Genre', array('label' => 'Genre(s) :'));
	 ?> 
     
    <fieldset>
        <legend>Biographies</legend>
        <?php
        $descriptions = Set::combine($this->data['Biographies'], '{n}.locale', '{n}.content');
     
        foreach(Configure::read('Config.languages') as $codeLang => $locale):
            e("<div>Biographie ($codeLang) :<br /><br />");
            e($form->textarea(
                'Artist.bio.'.$locale,
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
