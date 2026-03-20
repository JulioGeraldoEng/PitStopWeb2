<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo - Venda #{{ $venda->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .recibo {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 5px;
        }
        .produtos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .produtos th, .produtos td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .produtos th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body {
                padding: 0;
            }
            .btn-print {
                display: none;
            }
        }
        .btn-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-print button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="btn-print">
        <button onclick="window.print()">🖨️ Imprimir Recibo</button>
    </div>

    <div class="recibo">
        <div class="header">
            <h1>PitStopWeb</h1>
            <p>Av. Principal, 123 - Centro</p>
            <p>Tel: (11) 99999-9999 | Email: contato@pitstopweb.com</p>
            <p>CNPJ: 00.000.000/0001-00</p>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td width="50%"><strong>RECIBO DE VENDA</strong></td>
                    <td width="50%" align="right"><strong>Nº: {{ $venda->id }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Data:</strong> {{ date('d/m/Y', strtotime($venda->data)) }}</td>
                    <td align="right"><strong>Hora:</strong> {{ date('H:i:s', strtotime($venda->created_at)) }}</td>
                </tr>
                <tr>
                    <td><strong>Cliente:</strong> {{ $venda->cliente->nome }}</td>
                    <td align="right"><strong>Telefone:</strong> {{ $venda->cliente->telefone ?? '-' }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Endereço:</strong> {{ $venda->cliente->endereco ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <table class="produtos">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unit.</th>
                    <th>Subtotal</th>
                </thead>
                <tbody>
                    @foreach($venda->itens as $item)
                    <tr>
                        <td>{{ $item->nome_produto }}</td>
                        <td class="text-center">{{ $item->quantidade }}</td>
                        <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                        <td><strong>R$ {{ number_format($venda->total, 2, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="total">
                @if($venda->status == 'pendente')
                    <p><strong>Status:</strong> Pendente</p>
                    <p><strong>Vencimento:</strong> {{ $venda->data_vencimento ? date('d/m/Y', strtotime($venda->data_vencimento)) : '-' }}</p>
                @elseif($venda->status == 'pago')
                    <p><strong>Status:</strong> PAGO</p>
                @else
                    <p><strong>Status:</strong> CANCELADO</p>
                @endif
            </div>

            <div class="footer">
                <p>Este recibo é válido como comprovante de compra.</p>
                <p>Obrigado pela preferência!</p>
            </div>
        </div>
    </body>
    </html>