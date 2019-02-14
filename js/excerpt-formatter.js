(function($) {
  $('.jc-portfolio-grid-item-excerpt').each(function(i, el) {
    var $el = $(el)
    var specsArr = $el.text().match(/[A-Z]+[^A-Z]*|[^A-Z]+/g)
    specsArr = $el.text().split(', ')

    var formattedSpecs = specsArr.map(function(string, i) {
      if (i === 0) {
        return '<h6>' + string + '</h6>'
      }
      return string + '<br/>'
    })

    $el.empty()
    $el.prepend(formattedSpecs)
  })
})(jQuery)
