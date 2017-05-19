<div class="accordion" id="collapse-group">
    <div class="accordion-group widget-box">
        <div class="accordion-heading">
            <div class="widget-title">
                <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                    <span class="icon"><i class="icon-list"></i></span><h5>Dados do Produto</h5>
                </a>
                <a href='<?php echo base_url();?>/index.php/produtos/editar/<?php echo $result->idProdutos;?>' class="btn btn-info tip-top pull-right" title="Editar Produto"><i class="icon-pencil icon-white"></i></a>
            </div>
        </div>
        <div class="collapse in accordion-body">
            <div class="widget-content">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td style="text-align: right; width: 30%"><strong>Descrição</strong></td>
                            <td><?php echo $result->descricao ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Unidade</strong></td>
                            <td><?php echo $result->unidade ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Preço de Compra</strong></td>
                            <td>R$ <?php echo $result->precoCompra; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Preço de Venda</strong></td>
                            <td>R$ <?php echo $result->precoVenda; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Estoque</strong></td>
                            <td><?php echo $result->estoque; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Estoque Minimo</strong></td>
                            <td> <?php echo $result->estoqueMinimo; ?></td>
                        </tr>						
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

