<?php
$pdo=new PDO("mysql:host=localhost;dbname=produtos;charset=utf8","root","");
if($_SERVER["REQUEST_METHOD"]==="POST"&&isset($_POST["nome"],$_POST["preco"])){
if(isset($_POST["id"])){//update
$stmt=$pdo->prepare("UPDATE produtos SET nome=?,preco=?,descricao=? WHERE id=?");
$stmt->execute([$_POST["nome"],$_POST["preco"],$_POST["descricao"],$_POST["id"]]);
}else{//create
$stmt=$pdo->prepare("INSERT INTO produtos (nome,preco,descricao) VALUES (?,?,?)");
$stmt->execute([$_POST["nome"],$_POST["preco"],$_POST["descricao"]]);
}header("Location: index.php");exit;}
if(isset($_GET["deletar"])){
$pdo->prepare("DELETE FROM produtos WHERE id=?")->execute([$_GET["deletar"]]);
header("Location: index.php");exit;}
$produtos=$pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll();
$edit=null;if(isset($_GET["editar"])){$edit=$pdo->prepare("SELECT * FROM produtos WHERE id=?");
$edit->execute([$_GET["editar"]]);$edit=$edit->fetch();}
?><!DOCTYPE html><html lang="pt-BR">
<head><meta charset="UTF-8"><title>Produtos</title>
<style>*{box-sizing:border-box}body{font-family:Arial;max-width:800px;margin:20px auto;padding:20px}
form{background:#f5f5f5;padding:20px;border-radius:8px;margin-bottom:20px}
input,textarea{width:100%;padding:8px;margin:5px 0;border:1px solid #ddd;border-radius:4px}
button{background:#4CAF50;color:white;border:none;padding:10px 20px;border-radius:4px;cursor:pointer}
table{width:100%;border-collapse:collapse}th,td{padding:10px;text-align:left;border-bottom:1px solid #ddd}
th{background:#f5f5f5}.actions a{margin-right:10px;color:#2196F3;text-decoration:none}</style></head>
<body>
<h1>Produtos</h1>
<form method="POST"><h3><?=$edit?"Editar":"Novo"?> Produto</h3>
<?php if($edit):?><input type="hidden" name="id" value="<?=$edit["id"]?>"><?php endif;?>
<input type="text" name="nome" placeholder="Nome" value="<?=$edit["nome"]??""?>" required>
<input type="number" step="0.01" name="preco" placeholder="Preco" value="<?=$edit["preco"]??""?>" required>
<textarea name="descricao" placeholder="Descricao"><?=$edit["descricao"]??""?></textarea>
<button type="submit"><?=$edit?"Atualizar":"Salvar"?></button></form>
<table><tr><th>Nome</th><th>Preco</th><th>Acoes</th></tr>
<?php foreach($produtos as $p):?><tr>
<td><?=htmlspecialchars($p["nome"])?></td><td>R$<?=number_format($p["preco"],2,",",".")?></td>
<td class="actions"><a href="?editar=<?=$p["id"]?>">Editar</a><a href="?deletar=<?=$p["id"]?>" onclick="return confirm('Deletar?')">Deletar</a></td>
</tr><?php endforeach;?></table></body></html>
