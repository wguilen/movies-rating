<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if($usuarios->count() <= 0): ?>
    <div class="container-fluid">
        <p>
            Não há usuários cadastrados no sistema. Para cadastrar um agora, clique <?= anchor('usuario/cadastrar', 'aqui'); ?>.
        </p>
    </div>
<?php else: ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="usuario">Usuário</label>
                    <select class="form-control" id="usuario">
                        <?php foreach($usuarios as $usuario): ?>
                            <option value="<?= $usuario['email']; ?>"><?= $usuario['nome']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>        
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <table id="filmes-indicados" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Filme</th>
                            <th>Ano</th>
                            <th>Diretor</th>
                            <th>Gênero</th>
                            <th>IMDB (nota)</th>
                            <th>Tags</th>
                            <th>Avaliação média</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        'use strict';

        (function() {
            var $form = {
                filmes:     $('table#filmes-indicados tbody'),
                usuario:    $('select#usuario')
            };

            $(function() {
                usuarioChangeListener();
            });

            function usuarioChangeListener()
            {
                $form.usuario.change(function() {
                     $.ajax({
                         url:   '<?= site_url('filme/obterIndicacoesUsuario'); ?>',
                         data:  { 'email': $(this).val() },
                         type:  'POST'
                     }).success(function(filmes) {
                         $form.filmes.empty();
                         
                         if (filmes.length > 0)
                         {
                             for (var filme in filmes)
                             {
                                 var tags = [];

                                 if (filmes[filme].tags instanceof Array)
                                 {
                                     tags = filmes[filme].tags;
                                 }
                                 else
                                 {
                                     $.each(filmes[filme].tags, function(ind, elem) {
                                        tags.push(elem);
                                     });
                                 }

                                 var $filme = {
                                    filme:      filmes[filme].nome,
                                    ano:        filmes[filme].ano,
                                    diretor:    filmes[filme].diretor,
                                    genero:     filmes[filme].genero,
                                    imdb:       filmes[filme].imdb,
                                    tags:       tags,
                                    avaliacao:  filmes[filme].avaliacaoMedia
                                 };
                                 
                                 var $tr = $('<tr></tr>');
                                 for (var key in $filme)
                                 {
                                     var $td = $('<td>' + $filme[key] + '</td>');
                                     $tr.append($td);
                                 }                                 

                                 $form.filmes.append($tr);
                             }
                         }
                         else
                         {
                             var $tr = $('<tr></tr>').append('<td colspan="7">Sem filmes a serem indicados.</td>');
                             $form.filmes.append($tr);
                         }
                     });
                }).trigger('change');
            };
        })();
    </script>
<?php endif; ?>