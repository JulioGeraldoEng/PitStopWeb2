@extends('layouts.app')

@section('title', 'Backup')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Gerenciamento de Backup</h1>
        <hr>
    </div>
</div>

<div class="row">
    <!-- Card de Informações -->
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">📦 Tamanho do Banco</h5>
                <p class="card-text display-6">{{ $tamanhoBanco }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">📋 Último Backup</h5>
                @if($ultimoBackup)
                    <p class="card-text">{{ $ultimoBackup['name'] }}</p>
                    <small>{{ $ultimoBackup['date'] }}</small>
                @else
                    <p class="card-text">Nenhum backup realizado</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">💾 Total de Backups</h5>
                <p class="card-text display-6">{{ count($backups) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Backups Disponíveis</h3>
                <div>
                    <form action="{{ route('backups.create') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">➕ Criar Backup Agora</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                @if(count($backups) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                    <th>Arquivo</th>
                                    <th>Data</th>
                                    <th>Tamanho</th>
                                    <th width="200">Ações</th>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                    <tr>
                                        <td>{{ $backup['name'] }}</td>
                                        <td>{{ $backup['date'] }}</td>
                                        <td>{{ $backup['size'] }}</td>
                                        <td>
                                            <a href="{{ route('backups.download', basename($backup['path'])) }}" class="btn btn-sm btn-success">⬇️ Baixar</a>
                                            <form action="{{ route('backups.restore', basename($backup['path'])) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Restaurar este backup? O banco atual será substituído.')">🔄 Restaurar</button>
                                            </form>
                                            <form action="{{ route('backups.destroy', basename($backup['path'])) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este backup?')">🗑️ Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            Nenhum backup encontrado. Clique em "Criar Backup Agora" para começar.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">ℹ️ Informações</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li>✅ <strong>Backup automático:</strong> Todos os dias às 2h da manhã (configurado no servidor)</li>
                    <li>✅ <strong>Backup manual:</strong> Clique no botão "Criar Backup Agora"</li>
                    <li>✅ <strong>Restauração:</strong> Escolha um backup e clique em "Restaurar"</li>
                    <li>✅ <strong>Download:</strong> Baixe o arquivo .sqlite para seu computador</li>
                    <li>⚠️ <strong>Atenção:</strong> Restaurar um backup substituirá todos os dados atuais!</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection