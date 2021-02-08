<html>
    <body>
        <table>
            <thead>
                <th> ID </th>
                <th> Code </th>
                <th> Parent ID </th>
                <th> Name </th>
                <th> Level </th>
            </thead>
            <tbody>
                
                @foreach ($offices as $office)
                <tr>
                    <td>tbd</td>
                    <td>{{$office->code}}-AO</td>
                    <td>{{$office->id}}</td>
                    <td>{{$office->code}}-AO</td>
                    <td>account_officer</td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </body>
</html>