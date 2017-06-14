<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if($usuarios->count() <= 0): ?>
    <div class="container-fluid">
        <p>
            Não há usuários cadastrados no sistema. Para cadastrar um agora, clique <?= anchor('usuario/cadastrar', 'aqui'); ?>.
        </p>
    </div>
<?php else: ?>
    <link rel="stylesheet" href="<?= base_url('assets/rateyo-2.1.1/jquery.rateyo.min.css'); ?>"/>
    <script src="<?= base_url('assets/rateyo-2.1.1/jquery.rateyo.min.js') ?>"></script>

    <div class="container">
        <?= form_open('filme/avaliar') ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="usuario">Usuário</label>
                        <select id="usuario" name="usuario" class="form-control" required>
                            <?php foreach($usuarios as $usuario): ?>
                                <option value="<?= $usuario['email']; ?>" <?= set_select('usuario', $usuario['email']); ?>><?= $usuario['nome']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('usuario'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-9">
                    <div class="form-group">
                        <label for="filme">Filme</label>
                        <select id="filme" name="filme" class="form-control" required></select>
                        <?= form_error('filme'); ?>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="form-group pull-right text-right">
                        <label>Avaliação</label>
                        <?= form_hidden('avaliacao', set_value('avaliacao', 0)); ?>
                        <div id="movie-stars"></div>
                        <?= form_error('avaliacao'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-right">
                    <a href="<?= site_url('filme'); ?>" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        <?= form_close() ?>
    </div>

    <script>
        'use strict';

        (function() {
            var $form = {
                avaliacao:  $('input[name="avaliacao"]'),
                filme:      $('select#filme'),
                usuario:    $('select#usuario')
            };

            $(function() {
                usuarioChangeListener();
                ratingListener();
            });

            function ratingListener()
            {
                var $rating = $('div#movie-stars');

                $rating.rateYo({
                    rating:     $form.avaliacao.val(),
                    fullStar:   true
                });

                $rating.click(function() {
                    $form.avaliacao.val($rating.rateYo('rating'));
                });
            };

            function usuarioChangeListener()
            {
                $form.usuario.change(function() {
                     $.ajax({
                         url:   '<?= site_url('filme/obterFilmesUsuario'); ?>',
                         data:  { 'email': $(this).val() },
                         type:  'POST'
                     }).success(function(filmes) {
                         $form.filme.empty();

                         if (filmes.length > 0)
                         {
                             $(filmes).each(function(ind, elem) {
                                 var $html = $('<option value="' + elem._id['$id'] + '">' + elem.nome + '</option>');
                                 $form.filme.append($html);
                             });
                         }
                     });
                }).trigger('change');
            };
        })();
    </script>
<?php endif; ?>