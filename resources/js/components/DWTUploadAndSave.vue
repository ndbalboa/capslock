<script setup>
import { ref } from "vue";

import { DwtUIOperations } from "./tools/dwtUIOperations"
import { getEl, getInputEl } from "./tools/common";

const props = defineProps({
  dwtUtil: {
    required: true,
    type: DwtUIOperations
  }
});

const strFileNameWithoutExt = ref("WebTWAINImage");
const strExtension = ref("pdf");
const strAllPages = ref("true");

function handleFileNameChange(evt) {

  let el = evt.target;
  strFileNameWithoutExt.value = el.value;

}

function handleSaveConfigChange(evt) {

  let el = evt.target;
  let strNewExtension = el.value;
  strExtension.value = strNewExtension;
  onchangeImageExtension(strExtension.value);

}

function onchangeImageExtension(strExtension) {

  let currentPage = getInputEl("CurrentPage");
  let allPages = getInputEl("AllPages");

  // set AllPages OR CurrentPage
  switch (strExtension) {
    case "pdf":
      strAllPages.value = "true";
      break;
    case "tif":
    case "bmp":
    case "jpg":
    case "png":
      strAllPages.value = "false";
      break;
    default:
      break;
  }

  // set Enabled
  if (strExtension == "pdf" || strExtension == "tif") {
    if (currentPage) {
      currentPage.disabled = false;
    }

    if (allPages) {
      allPages.disabled = false;
    }
  } else {
    if (currentPage) {
      currentPage.disabled = true;
    }

    if (allPages) {
      allPages.disabled = true;
    }
  }
}


function saveOrUploadImage(saveType) {

  props.dwtUtil.save(saveType, strFileNameWithoutExt.value, strExtension.value, strAllPages.value);
}


function showCustomInfo() {

  let el = getEl("customDetail");
  if (el)
    el.style.display = "";
}

function hideCustomInfo() {

  let el = getEl("customDetail");
  if (el)
    el.style.display = "none";
}

function onclickShowUploadedFiles() {
  props.dwtUtil.onclickShowUploadedFiles();
}

function onclickSaveDocuments() {
  props.dwtUtil.onclickSaveDocuments();
}

</script>
<template>
<div>
  <div id="divUpload" class="divinput mt30" style="position:relative">
    <ul>
      <li>
        <div class="divType">
          Save Documents
        </div>
      </li>
    </ul>
  </div>
  <div id="tabCon" class="divinput mt30">
    <div id="divSaveDetail">
      <ul>
        <li>
          <p>File Name:</p>
          <input type="text" size="20" id="txt_fileName" v-model="strFileNameWithoutExt"
            v-on:change="handleFileNameChange($event)" /><span> . </span>
          <select size="1" id="fileType" style="position:relative;width: 25%;" v-model="strExtension"
            v-on:change="handleSaveConfigChange($event)">
            <option value="pdf">pdf</option>
            <option value="tif">tif</option>
            <option value="jpg">jpg</option>
            <option value="png">png</option>
            <option value="bmp">bmp</option>
          </select>
        </li>
        <li>
          <span> Pages: </span>
          <label for="CurrentPage" style="margin-left:5px;"><input type="radio" id="CurrentPage" name="Pages"
              v-model="strAllPages" value=false />Current
            Page&nbsp;</label>
          <label for="AllPages"><input type="radio" id="AllPages" name="Pages" v-model="strAllPages" value=true />All
            Pages</label>
        </li>
        <li>
          <span class="customInfo" v-on:mouseover="showCustomInfo()" v-on:mouseout="hideCustomInfo()">Optional
            Custom Info <i class="fa fa-download"></i></span> :
          <div style="display:none;" id="customDetail">You can input any custom info to be uploaded with your
            images.</div>
          <input type="text" id="txt_CustomInfo" />
        </li>
        <li>
          <input id="btnUpload" class="btnOrg" type="button" value="Upload" v-on:click="saveOrUploadImage('server')" />
          <a id="btnSave" class="btnLoadAndSave" v-on:click="saveOrUploadImage('local')">Save to Local Drive &gt;</a>
        </li>
      </ul>
    </div>
    <div id="divUploadedFiles" style="display:none;">
      <div id="resultWrap"></div>
    </div>
  </div>
</div>
</template>
