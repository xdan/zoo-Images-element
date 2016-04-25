<?php
class ElementImages extends Element {
	public function edit() {
		$this->app->document->addScript('elements:images/images.js');
		$this->app->document->addStyleSheet('elements:images/images.css');
		$html = array();
		$html[] = '<input type="hidden" class="images_collector_field" name="'.$this->getControlName('image_files').'" value="'.$this->get('image_files').'"/>';
		$html[] = '<input type="hidden" class="image_titles_collector_field" name="'.$this->getControlName('image_titles').'" value="'.$this->get('image_titles').'"/>';
		$html[] = '<div data-root="'.JURI::root().'" class="images_selector_by_xdsoft">';
		$files = explode('|', $this->get('image_files'));
		$titles = explode('|', $this->get('image_titles'));
		if (!count($files)) {
			$files = array('');
		}
		if (!count($titles)) {
			$titles = array('');
		}
        JFormHelper::addFieldPath(dirname(__FILE__));

		$form = new JForm('empty');
		$folder = JFormHelper::loadFieldType('folderlistdeep', true);
        
		$folder->setForm($form);
		$folder->setup(simplexml_load_string('<field directory="images" class="image_selector_folder"/>'), null);
		$folder->id = 'xdsoft_image_folder1';
		$folder->name = $this->getControlName('image_folder');
		$folder->value = $this->get('image_folder');
		$html[] = '<p>Выберите папку</p>';
		$html[] = '<div class="images_item">';
		$html[] = $folder->renderField();
		$html[] = '</div>';
		$html[] = '<p>Либо выбрать изображения по отдельности</p>';
		foreach ($files as $i=>$file) {
			$media = JFormHelper::loadFieldType('mediax', true);
			$media->setForm($form);
			$media->setup(simplexml_load_string('<field class="image_selector_field"/>'), null);
			$media->id = 'xdsoft_image'.$i;
			$media->name = 'xdsoft_image[]';
			$media->value = $file;
			$html[] = '<div class="images_item">';
			$html[] = $media->renderField();
			$html[] = '<input placeholder="Описание" name="xdsoft_image_title[]" type="text" class="image_title_field" value="'.htmlspecialchars($titles[$i]).'">';
			$html[] = '<a class="copy" href="javascript:void(0)">Копировать</a>';
			$html[] = '<a class="delete" href="javascript:void(0)">Удалить</a>';
			$html[] = '<div class="image-preview">'.($file?'<img src="'.JURI::root().$file.'" alt="">':'').'</div>';
			$html[] = '</div>';
		}
		$html[] = '</div>';
		return implode("\n", $html);
	}
	public function render($params = array()) {
		return $this->get('image_files');
	}
	public function hasValue($params = array()) {
		$street = $this->get('image_files');
		return !empty($street);
	}
}