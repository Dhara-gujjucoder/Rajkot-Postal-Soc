<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
    <body>
        <table>
            <tr>
                <td>Hii,{{$member_name}} </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" align="left">Please collect below access.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th align="left">URL: </th>
                <td align="left">{{ 'https://rajkotpostalsoc.in/user/login' }}</td>
            </tr>
            <tr>
                <th align="left">Registration No:</th>
                <td align="left">{{ $member_reg }}</td>
            </tr>
            <tr>
                <th align="left">Password:</th>
                <td align="left">{{ $password }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="left">Thanks.</td>
            </tr>
            <tr>
                <td colspan="2">Support Rajkotpostalsoc.</td>
            </tr>
        </table>
    </body>
</html>


