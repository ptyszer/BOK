<div class="row" id="content">
    <div class="col-md-12">
        <div class="page-header">
            <h2>Załóż konto</h2>
        </div>
        <form class="form-inline" action="" method="post">
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" class="form-control" id="login" name="login">
            </div>
            <div class="form-group">
                <label for="pwd">Hasło:</label>
                <input type="password" class="form-control" id="pwd" name="password">
            </div>
            <button type="submit" class="btn btn-default">Załóż konto</button>
            <br>
            <div class="radio-inline">
                <label><input type="radio" name="role" value="client" checked="checked">Klient</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="role" value="support">Support</label>
            </div>
        </form>
        <p>{{info}}</p>
    </div>
</div>