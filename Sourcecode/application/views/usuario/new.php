<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <?= form_open('usuario/cadastrar') ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="E-mail" value="<?= set_value('email'); ?>"/>
                    <?= form_error('email'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="nome" class="form-control" id="nome" name="nome" required placeholder="Nome completo" value="<?= set_value('nome'); ?>"/>
                    <?= form_error('nome'); ?>
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="genero">Gênero</label>
                    <select id="genero" name="genero" class="form-control" required>
                        <option value="Feminino" <?= set_select('genero', 'Feminino'); ?>>Feminino</option>
                        <option value="Masculino" <?= set_select('genero', 'Masculino'); ?>>Masculino</option>
                    </select>
                    <?= form_error('genero'); ?>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="form-group">
                    <label for="idade">Idade</label>
                    <input type="number" class="form-control" id="idade" name="idade" required placeholder="Idade" value="<?= set_value('idade'); ?>"/>
                    <?= form_error('idade'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="generos-filmes">Gêneros preferidos para filmes</label>
                    <select multiple class="form-control" id="generos-filmes" name="generosFilmes[]" required>
                        <?php foreach($generosFilmes as $generoFilmes): ?>
                            <option value="<?= $generoFilmes; ?>" <?= set_select('generosFilmes[]', $generoFilmes); ?>><?= $generoFilmes; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_error('generosFilmes[]'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-right">
                <a href="<?= site_url('usuario'); ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </div>
    <?= form_close() ?>
</div>