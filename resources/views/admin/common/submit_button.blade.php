@if($box_style)
<div class="box">
@endif
    <div class="box-footer">
        <button class="ladda-button" type="submit" id="submitBtn" data-style="expand-right" data-size="xs">
            <span class="ladda-label">{{ $button_label }}</span>
        </button>
    </div>
@if($box_style)
</div>
@endif