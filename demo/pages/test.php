<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Test page</title>
	<link rel="stylesheet" href="">
</head>
<body>
	<h1>Test</h1>
	<?=$this->component('nav')->flush();?>
	<p>Route parameter is <b><?=$this->route->param;?></b></p>
	<p>Additionnal query paramater is <b><?=$this->request->get('test',"default value if not set in url");?></b></p>
</body>
</html>