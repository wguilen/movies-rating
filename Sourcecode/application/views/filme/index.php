<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    Para cadastrar um filme, clique <?= anchor('filme/cadastrar', 'aqui') ?>.
</div>

<br/>

<div class="container-fluid">
    <?php if($filmes->count() > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <th>Nome</th>
                <th class="hidden-xs">Ano</th>
                <th>Gênero</th>
                <th class="hidden-xs">Faixa Etária</th>
                <th class="hidden-xs">Diretor</th>
                <th>IMDB</th>
                <th class="hidden-xs">Tags</th>
                <th class="text-right" style="min-width: 150px;">Ações</th>
            </thead>
            <tbody>
                <?php foreach($filmes as $filme): ?>
                    <tr>
                        <td><?= $filme['nome'] ?></td>
                        <td class="hidden-xs"><?= $filme['ano'] ?></td>
                        <td><?= $filme['genero'] ?></td>
                        <td class="hidden-xs"><?= $filme['faixaEtaria'] ?></td>
                        <td class="hidden-xs"><?= $filme['diretor'] ?></td>
                        <td><?= $filme['imdb'] ?></td>
                        <td class="hidden-xs">
                            <ul style="list-style: square;">
                                <?php foreach($filme['tags'] as $tag): ?>
                                    <li><?= $tag ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td class="text-right">
                            <a href="<?= site_url("filme/{$filme['_id']}/editar"); ?>" class="btn btn-default btn-sm">Editar</a>
                            <?= form_open("filme/remover", array('style' => 'display: inline;')); ?>
                                <?= form_hidden('_id', $filme['_id']); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            <?= form_close(); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>