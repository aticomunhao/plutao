<!DOCTYPE html>
<html>

<head>
  <style>
    table,
    td,
    th {
      border: 1px solid black;
      font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    .tabe {
      width: 50%;
      font-size: 12px;
      text-align: left;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      border: 2px solid #025E73;
    }

    td {
      text-align: center;
    }

    p {
      margin: 5px;
      text-align: justify;
      line-height: 150%;

    }

    .assinatura {
      text-align: center;

    }

    .autorizacao {
      text-align: center;
      font-size: 15px;
    }

    .data {
      text-align: center;
    }
    .h8{
      text-align: center;
    }
  </style>
</head>

<body>


  <table>
    <tr>
      <th class="tabe">
        <h8>COMUNHAO ESPÍRITA DE BRASÍLIA<br>
        Diretoria Administrativa e Financeira - DAF</h8>
      </th>
      <th class="tabe">
        <p class="autorizacao">AUTORIZAÇÃO PARA DÉBITO EM<br> CONTA CORRENTE - BANCO DO BRASIL</p>
      </th>

  </table>
  <br>
  <table>
    <th>
      <p> Eu, <span style="text-decoration: underline;">{{$associado->nome_completo}}</span> portador (a) da Carteira de Identidade nº <span style="text-decoration: underline;">{{$associado->idt}}</span> e CPF <span style="text-decoration: underline;">{{$associado->cpf}}</span>, residente em <span style="text-decoration: underline;">{{$associado->descricao}}, {{$associado->bairro}}, {{$associado->numero}}</span> CEP <span style="text-decoration: underline;">{{$associado->cep}}</span> telefone <span style="text-decoration: underline;">{{$associado->celular}}</span> e e-mail <span style="text-decoration: underline;">{{$associado->email}}</span> AUTORIZO a Comunhão Espírita de Brasília a efetuar mensalmente, no dia <span style="text-decoration: underline;">{{substr($associado->dt_vencimento, -2)}}</span>, débito em minha Agência nº <span style="text-decoration: underline;">{{$tpagencia->agencia}}</span> Conta Corrente nº <span style="text-decoration: underline;">{{$associado->nr_cont_corrente}}</span> do Banco do Brasil S.A., o valor da R$<span style="text-decoration: underline;">{{ $associado->valor }}</span> referente ao pagamento de mensalidade na qualidade de associado nº<span style="text-decoration: underline;">{{$associado->nr_associado}}</span> da Comunhão Espírita de Brasília.</p>
      @php
      $meses = [
      1 => 'Janeiro',
      2 => 'Fevereiro',
      3 => 'Março',
      4 => 'Abril',
      5 => 'Maio',
      6 => 'Junho',
      7 => 'Julho',
      8 => 'Agosto',
      9 => 'Setembro',
      10 => 'Outubro',
      11 => 'Novembro',
      12 => 'Dezembro'
      ];
      $mesAtual = intval(date('n'));
      @endphp

      <p class="data"> Brasília, <span style="text-decoration: underline;">{{ date('d') }}</span> de <span style="text-decoration: underline;">{{ $meses[$mesAtual] }}</span> de <span style="text-decoration: underline;">{{ date('Y') }}</span></p>


      <br>
      <p class="assinatura">_______________________________________________________</p>
      <br>
    </th>
  </table>
</body>

</html>
