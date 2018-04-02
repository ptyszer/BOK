<div class="row" id="content">
    <div class="col-md-4 colHeight">
        <div class="page-header">
            <h3>Wątki</h3>
        </div>
        <div>
            {{conversations}}
        </div>
    </div>
    <div class="col-md-8 colHeight">
        <div class="page-header">
            <h3>Chat</h3>
        </div>
        <div>
            {{messages}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <form class="form-inline" method="post" action="../controllers/ConversationController.php">
            <label for="conversationSubject" id="newConvSubject">Nowy temat:<br><input id="conversationSubject" name="conversationSubject" type="text" placeholder="Temat..."></label>
            <button class="btn">Dodaj...</button>
        </form>
    </div>
    {{message_form}}
</div>