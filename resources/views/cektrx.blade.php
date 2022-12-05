@extends('layout.index')

@section('content')
    <h1>All Data</h1>
    <div style="margin-top: 5px;">
        @if($data->count())
            @php($num = 1)
            <table>
                <tr>
                    <th>No</th>
                    <th>Trx ID</th>
                    <th>Trx At</th>
                    <th>Actions</th>
                </tr>
                @foreach($data as $isi)
                    @php($trx_id = json_decode($isi->log))
                    <tr>
                        <td>{{ $num++ }}</td>
                        <td>
                            <a href="{{ $trx_id->respon_url }}" target="_blank">{{ $trx_id->partnerTrxID }}</a>
                        </td>
                        <td>{{ $isi->created_at }}</td>
                        <td>
                            <form action="{{ url('/cektrx') }}" method="post">
                                @csrf
                                <input type="hidden" name="trx_id" value="{{ $isi->id }}">
                                <input type="hidden" name="trx_jenis" value="inquiry">
                                <button type="submit">Inquiry</button>
                            </form>
                            @if($isi->status == 'refunded')
                                <i>Refunded</i>
                                @elseif($isi->status == 'lunas')
                                <form action="{{ url('/cektrx') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="trx_id" value="{{ $isi->id }}">
                                    <input type="hidden" name="trx_jenis" value="refund">
                                    <button type="submit">Refund</button>
                                </form>
                                @else
                                <i>Proses</i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
@endsection
