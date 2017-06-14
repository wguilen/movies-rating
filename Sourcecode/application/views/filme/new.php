<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <?= form_open('filme/cadastrar') ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required placeholder="Nome do filme" value="<?= set_value('nome'); ?>"/>
                    <?= form_error('nome'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 col-md-2">
                <div class="form-group">
                    <label for="ano">Ano</label>
                    <input type="number" id="ano" name="ano" class="form-control" required placeholder="Ano" value="<?= set_value('ano'); ?>"/>
                    <?= form_error('ano'); ?>
                </div>
            </div>
            <div class="col-xs-8 col-md-4">
                <div class="form-group">
                    <label for="genero">Gênero</label>
                    <select id="genero" name="genero" class="form-control" required>
                        <?php foreach($generos as $genero): ?>
                            <option value="<?= $genero; ?>" <?= set_select('genero', $genero); ?>><?= $genero ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_error('genero'); ?>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="faixa-etaria">Faixa Etária</label>
                    <select id="faixa-etaria" name="faixaEtaria" class="form-control" required>
                        <?php foreach($faixasEtarias as $faixaEtaria => $descricao): ?>
                            <option value="<?= $faixaEtaria; ?>" <?= set_select('faixaEtaria', $faixaEtaria); ?>><?= $descricao ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_error('faixaEtaria'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group">
                    <label for="diretor">Diretor</label>
                    <input type="text" id="diretor" name="diretor" class="form-control" required placeholder="Diretor do filme" value="<?= set_value('diretor'); ?>"/>
                    <?= form_error('diretor'); ?>
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="imdb">IMDB (nota)</label>
                    <input type="number" id="imdb" name="imdb" class="form-control" step="0.01" required placeholder="Nota no IMDB" value="<?= set_value('imdb'); ?>"/>
                    <?= form_error('imdb'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" id="tags" name="tags" data-role="tagsinput" required placeholder="Tags" value="<?= set_value('tags'); ?>"/>
                    <?= form_error('tags'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-right">
                <a href="<?= site_url('filme'); ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </div>
    <?= form_close() ?>
</div>

<script>
    (function() {
        $(function() {
            $('input#tags').on('itemAdded', function() {
                $(this).tagsinput('focus');
            });
        });
    })();
</script>