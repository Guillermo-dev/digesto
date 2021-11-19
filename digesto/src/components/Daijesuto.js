/**
 *
 * @constructor
 */
export function Daijesuto() {
    const _theZone1 = document.getElementById("TheZone1");
    const _theZone2 = document.getElementById("TheZone2");
    let _counter = 10;

    /**
     *
     * @private
     */
    function _constructor() {
        _theZone1.onclick = _onClick;
        _theZone2.onclick = _onClick;
    }

    /**
     *
     * @private
     */
    function _onClick() {
        if (_counter > 0) {
            _counter--;
            return;
        }
        const audio = new Audio("/src/global/.hidden.mp3");
        audio.currentTime = 1.05;
        audio.play().then((r) => {});
        _counter = 10;
    }

    //Invoke
    _constructor();
}
