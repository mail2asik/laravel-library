@if($box_style)
<div class="box">
@endif
    <div class="box-footer">
        <button class="ladda-button" type="submit" id="submitBtn" data-style="expand-right" data-size="xs">
            <span class="ladda-label">{{ $submit_button_label }}</span>
        </button>

        <a href="{{ $delete_action_url }}" onclick="Ladda.create(document.querySelector('#deleteBtn')).start()">
        <button class="ladda-button" type="button" id="deleteBtn" data-style="expand-right" data-size="xs">
          <span class="ladda-label">{{ $delete_button_label }}</span>
        </button>
        </a>
    </div>
@if($box_style)
</div>
@endif