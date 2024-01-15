<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
    <body>
        <table>
            <tr>
                <td>Hii</td>
            </tr>
            <tr>
                <td colspan="2" align="left">Following are Member detail.</td>
            </tr>
            <tr>
                <th align="left">Member :</th>
                <td align="left">{{ $user->name }}</td>
            </tr>
            <tr>
                <th align="left">Member Id  :</th>
                <td align="left">{{ $member_id }}</td>
            </tr>
            <tr>
                <th align="left">Total Share  :</th>
                <td align="left">0</td>
            </tr>
            <tr>
                <th align="left">Outgoing Loan  :</th>
                <td align="left">0</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" align="left">Loan Inquiry detail.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th align="left">Loan Amount :</th>
                <td align="left">{{ $loan_amount }}</td>
            </tr>
            <tr>
                <th align="left">EMI Amount :</th>
                <td align="left">{{ $emi_amount }}</td>
            </tr>
            <tr>
                <th align="left">Interest Rate :</th>
                <td align="left">{{ $loan_interest }}</td>
            </tr>
            <tr>
                <th align="left">EMI Month(Around) :</th>
                <td align="left">{{ $emi_month_around }}</td>
            </tr>
            <tr>
                <th align="left">Interest Payable :</th>
                <td align="left">{{ $interest_pay }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="left">Thanks.</td>
            </tr>
        </table>
    </body>
</html>


