<html>
    <head>
        <title>Test Page</title>
    </head>
    
    <body>
        <form action="index.php" method="post">
        <table>
            <input type="hidden" name="action" value="register"/>
            <tr>
                <td>Phone Number</td>
                <td><input type="text" name="phonenumber"/></td>
            </tr>
            <tr>
                <td>GCM Id</td>
                <td><input type="text" name="gcmid"/></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit"/></td>
            </tr>
        </table>
        </form>
        
        <form action="index.php" method="post">
        <table>
            
            <tr>
                <td>Friend List</td>
                <td><input type="text" name="friendlist"/></td>
                <input type="hidden" name="action" value="getFriendList"/>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit"/></td>
            </tr>
        </table>
        </form>
        
        <form action="index.php" method="post">
        <table>
            
            <tr>
                <td>Friend List</td>
                <td><input type="text" name="friendlist"/></td>
                <input type="hidden" name="action" value="getFriendList"/>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit"/></td>
            </tr>
        </table>
        </form>
    </body>
</html>