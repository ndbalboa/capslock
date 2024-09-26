
export function getEl(elId) {
  return document.getElementById(elId);
}

export function getInputEl(elId) {
  return getEl(elId);
}

export function getSelectEl(elId) {
  return getEl(elId);
}


export function isString(o) {

  if (o === undefined || o === null)
    return false;

  return typeof (o) === 'string';
}

export function isNumber(o) {

  if (o === undefined || o === null)
    return false;

  let _num = parseInt(o);
  if (_num == 0 || _num > 0 || _num < 0)
    return true;

  return false;
}

export function elAddClass(element, className) {
  if (element) {
    element.classList.add(className);
  }
}
export function elRemoveClass(element, className) {
  if (element) {
    element.classList.remove(className);
  }
}

export function elHide(element) {
  if (element) {
    element.style.display = "none";
  }
}
export function elShow(element) {
  if (element) {
    element.style.display = "";
  }
}

let _browserInfo;
export function getBrowserInfo() {
  return _browserInfo;
}

export function initBrowserInfo(_navigator) {

  let _bWin = false,
    _bWin64 = false,
    _bLinux = false,
    _bMac = false,
    _bChromeOS = false,

    _bEdge = false,
    _bChrome = false,
    _bOpera = false,

    _bFirefox = false,
    _bSafari = false,
    _bIE = false,

    _bUseUserAgent = false;

  if ('userAgentData' in _navigator) {

    let aryBrands = [],
      uaData = _navigator.userAgentData,
      _platform = uaData.platform.toLowerCase(),
      _i, _count;

    if ('brands' in uaData && Array.isArray(uaData['brands']) && uaData['brands'].length > 0) {
      aryBrands = uaData.brands;
    } else if ('uaList' in uaData && Array.isArray(uaData['uaList']) && uaData['uaList'].length > 0) {
      aryBrands = uaData.uaList;
    }

    _count = aryBrands.length;

    if (_count == 0) {
      _bUseUserAgent = true;
    } else {

      for (_i = 0; _i < _count; _i++) {
        let elBrand = aryBrands[_i];
        let brand = elBrand.brand.toLowerCase();
        if (brand.indexOf("chrome") >= 0) {
          _bChrome = true;
          break;
        } else if (brand.indexOf("edge") >= 0) {
          _bEdge = true;
          break;
        } else if (brand.indexOf("opera") >= 0) {
          _bOpera = true;
          break;
        }
      }

      if (!_bChrome && !_bEdge && !_bOpera) {
        _bChrome = true;
      }

      _bMac = (_platform.indexOf('mac') >= 0);
      _bChromeOS = (_platform.indexOf('chrome os') >= 0);
      _bWin = (_platform == 'windows');
      _bLinux = (_platform == 'linux');

    }

  } else {
    _bUseUserAgent = true;
  }

  if (_bUseUserAgent) {

    let ua = navigator.userAgent.toLowerCase(),
      _platform = navigator.platform.toLowerCase(),
      _bHarmonyOS = (/harmonyos/g).test(ua),
      _tmp;

    if (_platform == 'linux' && ua.indexOf("windows nt") >= 0) {
      _platform = 'harmonyos';
      _bHarmonyOS = true;
    }

    // firefox & safari
    let _nFirefox = ua.indexOf('firefox');
    _bFirefox = (_nFirefox != -1);
    _tmp = ua.match(/version\/([\d.]+).*safari/);
    _bSafari = _tmp ? !0 : !1;
    _bChromeOS = !_bHarmonyOS && (/cros/).test(ua);
      
    let _bAndroid = !_bHarmonyOS && (/android/g).test(ua) || (/android/g).test(_platform),
      _biPhone = (/iphone/g).test(ua) || (/iphone/g).test(_platform),

      _bPadOrMacDesktop = (/macintosh/).test(ua),	// maybe iPad or MAC Desktop
      _maxTouchPoints = navigator.maxTouchPoints || 0,
      _biPad = (/ipad/g).test(ua) || ((_bPadOrMacDesktop || (_platform == 'macintel')) && _maxTouchPoints > 1),

      _bUC = (/ucweb|ucbrowser/g).test(ua),
      _bNexus = !_bUC && (/nexus/g).test(ua) && (/version\/[\d.]+.*safari\//g).test(ua),

      _bPlaybook = (/playbook/g).test(ua),
      _bHpTablet = (/hp-tablet/g).test(ua),
      _bBlackBerry = (/blackberry|bb10/g).test(ua),
      _bSymbian = (/symbian/g).test(ua),
      _bWindowsPhone = (/windows phone/g).test(ua),
      _bOtherMobile = (/mobile/g).test(ua);

    let _bPad = _bPlaybook || _biPad || _bHpTablet,
      _bMobile = !_bPad && !_bChromeOS && (_biPhone || _bNexus ||
        _bBlackBerry || _bSymbian || _bWindowsPhone || _bAndroid || _bHarmonyOS || _bOtherMobile),
      _bNotMobileOS = !_bMobile && !_bPad && !_bChromeOS,
      _nMSIE = ua.indexOf('msie'),
      _nTrident = ua.indexOf('trident'),
      _nRV = ua.indexOf('rv:'),
      _nEdge = ua.indexOf('edge'),
      _indexOfChrome = ua.indexOf('chrome');

    _bWin = _bNotMobileOS && (/win32|win64|windows/).test(_platform);
    _bEdge = _bWin && !_bFirefox && (_nEdge != -1);
    _bChrome = !_bEdge && (_indexOfChrome != -1);
    _bWin64 = _bWin && (/win64|x64/).test(ua);
    _bMac = _bNotMobileOS && (/mac68k|macppc|macintosh|macintel/).test(ua);
    _bLinux = _bNotMobileOS && (/linux/).test(_platform);
    _bIE = _bWin && !_bFirefox && !_bEdge && !_bChrome && (_nMSIE != -1 || _nTrident != -1 || _nRV != -1);

  }

  _browserInfo = {
    bWin: _bWin,
    bWin64: _bWin64,
    bLinux: _bLinux,
    bMac: _bMac,
    bChromeOS: _bChromeOS,

    bChrome: _bChrome,
    bEdge: _bEdge,
    bFirefox: _bFirefox,
    bIE: _bIE,
    bSafari: _bSafari
  };

  return _browserInfo;
}
