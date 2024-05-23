/**
* 雙擊td建立input更新內容
* url = update api url(不含參數id)
*/
function dblclickToEdit(url) {
    document.querySelectorAll('td').forEach(td => {
        td.addEventListener('dblclick', (e) => {

            // 取得當前td內容
            const originalContent = td.textContent;
            const id = e.target.getAttribute('data-id');
            const colName = e.target.getAttribute('data-name');

            // 創建input
            const input = document.createElement('input');
            input.type = 'text';
            input.value = originalContent;

            // 將input加到td中
            td.textContent = '';
            td.appendChild(input);

            // 自動聚焦並選中內容
            input.focus();
            input.select();

            input.addEventListener('keydown', (e) => {

                if (e.key === 'Enter' && input.value !== originalContent) {
                    $.ajax({
                        url: url + id,
                        type: 'POST',
                        data: {
                            _method: 'PATCH',
                            name: colName,
                            value: input.value.trim()
                        },
                        success: function (res) {
                            td.textContent = input.value.trim();
                            if (res.total !== undefined) {
                                const totalId = res.totalId !== undefined ? res.totalId : 'total';
                                const totalEl = document.getElementById(totalId);
                                totalEl.textContent = 'Total：$' + res.total;
                            }
                        },
                        error: function (err) {
                            console.log(err);
                            td.textContent = originalContent;
                        }
                    });
                }
            });

            // iinput 失去焦點時，保存內容並恢復td
            input.addEventListener('blur', () => {
                td.textContent = input.value.trim() || originalContent;
            });
        })
    })
}