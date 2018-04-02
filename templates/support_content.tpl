<div class="row" id="content">
    <div class="col-md-4 colHeight">
        <div class="page-header">
            <h3>Moje Wątki</h3>
        </div>
        <div>
            {{conversations}}
        </div>
    </div>
    <div class="col-md-4 colHeight">
        <div class="page-header">
            <h3>Otwarte wątki</h3>
        </div>
        <div>
            {{openConversations}}
        </div>
    </div>
    <div class="col-md-4 colHeight">
        <div class="page-header">
            <h3>Chat</h3>
        </div>
        <div>
            {{messages}}
        </div>
    </div>
</div>
<div class="row">
    {{message_form}}
</div>