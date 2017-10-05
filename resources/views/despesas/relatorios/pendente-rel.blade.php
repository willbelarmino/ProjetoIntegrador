<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
    <thead>
    <tr>
        <th>Nome</th>
        <th>Data de vencimento </th>
        <th>Valor </th>
        <th>Categoria </th>
    </tr>
    </thead>
    <tbody>

    @foreach ($parcelas as $parcela)

        <tr>
            <td>{{ $parcela->despesa->nome }}</td>

            <td>{{ date('d/m/Y', strtotime($parcela->dt_vencimento)) }}</td>

            <td>{{ 'R$ '.number_format($parcela->valor, 2, ',', '.') }}</td>

            <td>{{ $parcela->despesa->categoria->nome }}</td>

        </tr>
    @endforeach
    </tbody>
</table>