jQuery(document).ready(function() {
    jQuery('#record_label_track_name + .add').click(function() {
      addTrack();
    });
    jQuery('#record_label_track_name').keypress(function(e){
      if(e.keyCode == 13) {
        e.preventDefault();
        addTrack();
      }
    });
    jQuery(".remove").live('click', function() {
        jQuery(this).parent().parent().remove();
    });

    table = jQuery("table.sortable tbody#record_label_tracklist_prepend");
    if(table.size()) {
      table.sortable().disableSelection();
    }
});

function addTrack() {
  template = jQuery("#record_label_tracklist_template tr")[0].cloneNode(true);
  elements = jQuery('.record_label_track_row').length;
  track_name = jQuery("#record_label_track_name")[0].value;

  template.innerHTML = template.innerHTML.replace(/REPLACETHISNAME/g,track_name);

  jQuery('#record_label_tracklist_prepend').append(template);
  jQuery("#record_label_track_name").val("");
  jQuery("#record_label_track_name").focus();
  return false;
}

jQuery(document).ready(function() {
    jQuery('#record_label_link + .add').click(function() {
      addLink();
    });
    jQuery('#record_label_link').keypress(function(e){
      if(e.keyCode == 13) {
        e.preventDefault();
        addLink();
      }
    });
    jQuery(".remove").live('click', function() {
        jQuery(this).parent().parent().remove();
    });

    table = jQuery("table.sortable tbody#record_label_links_prepend");
    if(table.size()) {
      table.sortable().disableSelection();
    }
});

function addLink() {
  template = jQuery("#record_label_links_template tr")[0].cloneNode(true);
  elements = jQuery('.record_label_link_row').length;
  link = jQuery("#record_label_link")[0].value;

  if(storeName = storeNameFromUrl(link)) {
    template.innerHTML = template.innerHTML.replace(/REPLACENAME/g, storeName);
  } else {
    template.innerHTML = template.innerHTML.replace(/REPLACENAME/g,'');
  }

  template.innerHTML = template.innerHTML.replace(/http:\/\/REPLACEURL/g,link);
  template.innerHTML = template.innerHTML.replace(/REPLACEID/g,new Date().getTime());

  jQuery('#record_label_links_prepend').prepend(template);
  return false;
}

function storeNameFromUrl(url) {
  var stores = [
    {
      name: "bandcamp",
      match: /(bandcamp.com)/
    },
    {
      name: "itunes",
      match: /(itunes.apple.com)(.*)(\?app=itunes)/
    },
    {
      name: "apple music",
      match: /(itunes.apple.com)(.*)(\?app=music)/
    },
    {
      name: "deezer",
      match: /(deezer.com)/
    },
    {
      name: "spotify",
      match: /(spotify.com)/
    },
    {
      name: "amazon",
      match: /(amazon.com)/
    },
    {
      name: "google",
      match: /(google.com)/
    }
  ];


  var len = stores.length, i = 0;

  for (; i < len; i++) {
      if (url.match(stores[i]["match"])) {
          return stores[i]["name"];
      }
  }
  return false;
}
