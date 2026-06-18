const KEYS = [
    {
        group: 'Diacritics',
        keys: [
            { char: '̣', label: 'ạ', title: 'Dot below' },
            { char: '̲', label: 'a̲', title: 'Low line below' },
            { char: '̅', label: 'a̅', title: 'Overline' },
            { char: '̂', label: 'â', title: 'Caret'},
            { char: '', label: 'a', title: 'Acute accent' },
            { char: '', label: 'a', title: 'Grave accent' },
            { char: '', label: 'a', title: 'Circumflex accent' },
        ],
    },
    {
        group: 'Brackets',
        keys: [
            { char: '〚', label: '〚', title: 'Open double square bracket' },
            { char: '〛', label: '〛', title: 'Close double square bracket' },
            { char: '⟪', label: '⟪', title: 'Open double angle bracket' },
            { char: '⟫', label: '⟫', title: 'Close double angle bracket' },
            { char: '«', label: '«', title: 'Left guillemet (caporale)' },
            { char: '»', label: '»', title: 'Right guillemet (caporale)' },
        ],
    },
];

function insertAtCursor(textarea, char) {
    const start = textarea.selectionStart;
    const end   = textarea.selectionEnd;
    textarea.value = textarea.value.slice(0, start) + char + textarea.value.slice(end);
    textarea.selectionStart = textarea.selectionEnd = start + char.length;
    textarea.focus();
    textarea.dispatchEvent(new Event('input'));
}

function buildKeyboard(textarea) {
    const kb = document.createElement('div');
    kb.className = 'ifao-keyboard';

    KEYS.forEach(({ group, keys }) => {
        const groupEl = document.createElement('div');
        groupEl.className = 'ifao-keyboard-group';

        const label = document.createElement('span');
        label.className = 'ifao-keyboard-label';
        label.textContent = group;
        groupEl.appendChild(label);

        const keysEl = document.createElement('div');
        keysEl.className = 'ifao-keyboard-keys';

        keys.forEach(({ char, label: btnLabel, title }) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = btnLabel;
            btn.title = title;
            btn.addEventListener('mousedown', e => {
                e.preventDefault();
                insertAtCursor(textarea, char);
            });
            keysEl.appendChild(btn);
        });

        groupEl.appendChild(keysEl);
        kb.appendChild(groupEl);
    });

    textarea.insertAdjacentElement('afterend', kb);
}

const textArea = document.getElementById('text');
if (textArea) {
    textArea.classList.add('ifao-text');
    buildKeyboard(textArea);
}