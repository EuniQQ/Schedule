<div class="row offcanvas offcanvas-start" tabindex="-1" id="offcanvasWithBackdrop" aria-labelledby="offcanvasWithBackdropLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
        </button>
    </div>
    <div class="offcanvas-body menu">
        <div>
            <a href="{{ route('home') }}">HOME</a>
        </div>

        <div class="type">日&nbsp;&nbsp;誌</div>

        <div>
            <a href="{{ route('calender') }}">·&nbsp;當月月曆</a>
        </div>

        <div>
            <a href="{{ route('event.index') }}">·&nbsp;每月事紀</a>
        </div>

        <div>
            <a href="{{ route('journal') }}">·&nbsp;每日記事</a>
        </div>

        <div class="type">記&nbsp;&nbsp;帳</div>
        <div>·&nbsp;收入</div>
        <div>·&nbsp;支出</div>
        <div>·&nbsp;年度結餘</div>
        <div class="type"></div>
        <div>
            <a href="{{ route('profile.edit') }}">設&nbsp;&nbsp;定</a>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="logout">
            @csrf
            <button button type="submit" class="removeBtnSty">
                <strong>登&nbsp;&nbsp;出</strong>
            </button>
        </form>
    </div>
</div>