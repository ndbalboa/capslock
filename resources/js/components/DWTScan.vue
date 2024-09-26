<script setup>
import { reactive } from 'vue';

import { DwtUIOperations } from './tools/dwtUIOperations'
import { getSelectEl } from './tools/common';

const props = defineProps({
  dwtUtil: {
    required: true,
    type: DwtUIOperations
  }
});

let scanOptions = reactive({
  IfShowUI: false,
  IfFeederEnabled: true,
  IfAutoDiscardBlankpages: false,
  IfDuplexEnabled: false,
  PixelType: "1",
  Resolution: "200"
});


function onSourceChange(event) {

  let sourceEl = event.target;
  props.dwtUtil.handleDeviceChanged(sourceEl.value);

}

function acquireImage(evt) {

  let sourceEl = getSelectEl('source');
  props.dwtUtil.acquireImage(sourceEl?.value, scanOptions);

}

function loadImagesOrPDFs(evt) {

  props.dwtUtil.loadImage();

}
</script>

<template>
  <div id="divScanner" class="divinput">
    <ul class="PCollapse">
      <li>
        <div class="divType">
          Custom Scan
        </div>
        <div id="div_ScanImage" class="divTableStyle">
          <ul id="ulScaneImageHIDE">
            <li>
              <label for="source">
                <p>Select Source:</p>
              </label>
              <select size="1" id="source" style="position:relative;" v-on:change='onSourceChange($event)'>
              </select>
            </li>
            <li id="divProductDetail">
              <ul id='divTwainType'>
                <li>
                  <label style='width: 165px;' id='lblShowUI' for='showUI'>
                    <input type="checkbox" id="showUI" v-model='scanOptions.IfShowUI' />Show Scanner UI&nbsp;</label>

                  <label for='pageFeeder'>
                    <input type="checkbox" id="pageFeeder" v-model='scanOptions.IfFeederEnabled' />Use ADF&nbsp;</label>
                </li>
                <li><label style='width: 165px;' for='DiscardBlankPage'>
                    <input type='checkbox' id='DiscardBlankPage' v-model='scanOptions.IfAutoDiscardBlankpages' />Auto
                    Remove Blank
                    Page</label>
                  <label for='Duplex'><input type='checkbox' id='Duplex' v-model='scanOptions.IfDuplexEnabled' />2-sided
                    Scan</label>
                </li>
                <li>Pixel Type:<label for='BW' style='margin-left:5px;' class='lblPixelType'><input type='radio' id='BW'
                      name='PixelType' v-model='scanOptions.PixelType' value="0" />B&amp;W </label>
                  <label for='Gray' class='lblPixelType'><input type='radio' id='Gray' name='PixelType'
                      v-model='scanOptions.PixelType' value="1" />Gray</label>
                  <label for='RGB' class='lblPixelType'><input type='radio' id='RGB' name='PixelType'
                      v-model='scanOptions.PixelType' value="2" />Color</label>
                </li>
                <li>
                  <span>Resolution:</span>
                  <select class="custom-select w-50" id="Resolution" size='1' v-model='scanOptions.Resolution'>
                    <option value="100">100</option>
                    <option value="150">150</option>
                    <option value="200" selected>200</option>
                    <option value="300">300</option>
                  </select>
                </li>
              </ul>
            </li>
            <li>
              <input id="btnScan" class="btnScanGray btnScanActive" type="button" value="Scan"
                v-on:click='acquireImage($event)' />
              <a id="btnLoad" class="btnLoadAndSave" v-on:click='loadImagesOrPDFs($event)'>Import Local Images &gt;</a>
            </li>
          </ul>
          <div id="divNoScanners" style="visibility:hidden;">
            <a href="javascript: void(0)" class="ClosetblLoadImage"><img class="imgClose" :src="('assets/Images/Close.png')"
                alt="Close tblLoadImage" /></a>
            <img :src="('assets/Images/Warning.png')" />
            <span class="spanContent">
              <p class="contentTitle">No TWAIN compatible drivers detected</p>
              <p class="contentDetail">You can Install a Virtual Scanner:</p>
              <p class="contentDetail">
                <a id="samplesource32bit"
                  href="https://download.dynamsoft.com/tool/twainds.win32.installer.2.1.3.msi">32-bit Sample
                  Source</a>
                <a id="samplesource64bit" style="display:none;"
                  href="https://download.dynamsoft.com/tool/twainds.win64.installer.2.1.3.msi">64-bit Sample
                  Source</a> from <a target="_blank" href="http://www.twain.org">TWG</a>
              </p>
            </span>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>