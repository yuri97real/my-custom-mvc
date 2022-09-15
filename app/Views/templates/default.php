<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php $component("Head", compact("title")) ?>
</head>
<body>
    <?php require_once ROOT."/app/Views/pages/{$file}.php"; ?>
</body>
</html>