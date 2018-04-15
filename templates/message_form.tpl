<div class="col-md-4">
    <form class="form-inline newMessage" method="post"> <!--action="../controllers/MessageController.php"-->
        <input type="hidden" name="conversationId" value="{{conversationId}}" />
        <label for="messageText">Nowa wiadomość dla tematu "{{subject}}":<br><textarea name="messageText" rows="5" cols="30"></textarea></label>
        <br>
        <button class="btn">Wysłij</button>
    </form>
</div>