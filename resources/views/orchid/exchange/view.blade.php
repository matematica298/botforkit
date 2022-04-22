<style>
    table th {
        border: 1px solid #9a9a9a;
        background-color: #eaeaea;
        text-align: center;
        padding: 5px;
    }
    table td {
        border: 1px solid #9a9a9a;
        padding: 5px;
        text-align: center;
    }
</style>

<div style="background-color: white; padding: 20px; border-radius: 5px; box-shadow: 0 3px 5px 0 rgba(0,0,0,0.05)">
    @foreach($exchanges->sortBy('group_id') as $number => $groupExchanges)
        <strong style="font-size: 18px;">{{$number}}</strong>
        <table style="width: 100%; margin-bottom: 10px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 10%;">Пара</th>
                    <th style="width: 25%;">Заменяемый предмет</th>
                    <th style="width: 25%;">Заменяющий преподаватель</th>
                    <th style="width: 25%;">Новый предмет</th>
                    <th style="width: 15%;">Кабинет</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupExchanges->sortBy('order') as $groupExchange)
                    <tr>
                        <td><a href="{{ route('platform.exchange.edit', ['exchange' => $groupExchange->id]) }}">{{ $groupExchange->order }}</a></td>
                        <td>{{ $groupExchange->old_title }}</td>
                        <td>{{ $groupExchange->teacher->shortname ?? 'Не указан'}}</td>
                        <td>{{ $groupExchange->title }}</td>
                        <td>{{ $groupExchange->cab }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>
