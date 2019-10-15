<ul>
	<li><a href="<?=page_link('');?>"><?=page_link('');?></a></li>
	<li><a href="<?=page_link('test');?>"><?=page_link('test');?></a></li>
	<li><a href="<?=page_link('test/url-parameter-1');?>"><?=page_link('test/url-parameter-1');?></a></li>
	<li><a href="<?=page_link('test/url-parameter-2');?>"><?=page_link('test/url-parameter-2');?></a></li>
	<li><a href="<?=page_link('test/url-parameter-3',array("test"=>"An additionnal parameter"));?>"><?=page_link('test/url-parameter-3',array("test"=>"An additionnal parameter"));?></a></li>
	<li><a href="<?=page_link('non-existing-page');?>"><?=page_link('non-existing-page');?></a></li>
</ul>