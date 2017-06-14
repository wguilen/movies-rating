<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    Para cadastrar um usuário, clique <?= anchor('usuario/cadastrar', 'aqui') ?>.
</div>

<br/>

<div class="container-fluid">
    <?php if($usuarios->count() > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <th>Email</th>
                <th class="hidden-xs">Nome</th>
                <th class="hidden-xs">Gênero</th>
                <th class="hidden-xs">Idade</th>
                <th>Gêneros preferidos para filmes</th>
                <th class="text-right" style="min-width: 150px;">Ações</th>
            </thead>
            <tbody>
                <?php foreach($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['email'] ?></td>
                        <td class="hidden-xs"><?= $usuario['nome'] ?></td>
                        <td class="hidden-xs"><?= $usuario['genero'] ?></td>
                        <td class="hidden-xs"><?= $usuario['idade'] ?></td>
                        <td>
                            <ul style="list-style: square;">
                                <?php foreach($usuario['generosFilmes'] as $generoFilmes): ?>
                                    <li><?= $generoFilmes ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td class="text-right">
                            <a href="<?= site_url("usuario/{$usuario['_id']}/editar"); ?>" class="btn btn-default btn-sm">Editar</a>
                            <?= form_open("usuario/remover", array('style' => 'display: inline;')); ?>
                                <?= form_hidden('_id', $usuario['_id']); ?>
                                <?= form_hidden('email', $usuario['email']); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            <?= form_close(); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>