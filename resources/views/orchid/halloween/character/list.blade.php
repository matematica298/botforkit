<style>
    .group {
        background-color: white;
        padding: 20px;
        margin-bottom: 10px;
        border-radius: 5px;
        box-shadow: 0 3px 5px 0 rgba(0,0,0,0.05);
    }
    .flexGroup {
        display: flex;
        justify-content: space-around;
    }
    .subgroup {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .linesGroup {
        display: flex;
    }
    .line {
        height: 15px;
        opacity: 0.5;
    }
    .line.good {
        background-color: #11ab11;
        border-radius: 5px 0 0 5px;
    }
    .line.neutral {
        background-color: #a0aec0;
    }
    .line.evil {
        background-color: #ab1111;
        border-radius: 0 5px 5px 0;
    }
</style>

@php
    $good = $sides['good'] ?? 0;
    $neutral = $sides['neutral'] ?? 0;
    $evil = $sides['evil'] ?? 0;
    $sum = ($good + $neutral + $evil) || 1;
@endphp

<div class="group">
    <div class="flexGroup">
        <div class="subgroup">
            <strong>Положительные персонажи:</strong>
            <span>{{ $good }}</span>
        </div>
        <div class="subgroup">
            <strong>Нейтральные персонажи:</strong>
            <span>{{ $neutral }}</span>
        </div>
        <div class="subgroup">
            <strong>Отрицательные персонажи:</strong>
            <span>{{ $evil }}</span>
        </div>
    </div>
    <div class="linesGroup">
        <div class="good line" data-value="{{ ($good / $sum * 100) }}"></div>
        <div class="neutral line" data-value="{{ ($neutral / $sum * 100) }}"></div>
        <div class="evil line" data-value="{{ ($evil / $sum * 100) }}"></div>
    </div>
</div>

<script>
    document.querySelectorAll('.line').forEach((el) => {
        el.style.flexBasis = el.getAttribute('data-value') + '%';
    });
</script>
