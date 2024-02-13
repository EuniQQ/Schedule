<div class="row offcanvas offcanvas-start" tabindex="-1" id="offcanvasWithBackdrop" aria-labelledby="offcanvasWithBackdropLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
        </button>
    </div>
    <div class="offcanvas-body menu">
        <p><a href="{{ route('home') }}">HOME</a></p>
        <p class="type">日&nbsp;&nbsp;誌</p>
        <p><a href="{{ route('calender.index') }}">·&nbsp;當月月曆</a></p>
        <p>·&nbsp;每月事紀</p>
        <p><a href="{{ route('journal') }}">·&nbsp;每日記事</a></p>
        <p class="type">記&nbsp;&nbsp;帳</p>
        <p>·&nbsp;收入</p>
        <p>·&nbsp;支出</p>
        <p>·&nbsp;年度結餘</p>
        <p class="type">照&nbsp;&nbsp;片</p>
        <p>設&nbsp;&nbsp;定</p>
        <form method="POST" action="{{ route('logout') }}" class="logout">
            @csrf
            <button button type="submit" class="removeBtnSty">
                <strong>登&nbsp;&nbsp;出</strong>
            </button>
        </form>
    </div>
</div>