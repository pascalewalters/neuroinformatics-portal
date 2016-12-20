<h1>This is a Markdown page written in HTML</h1>

<div class="info">
  On GitLab, the following HTML tags are permitted:
  <blockquote>
    h1 h2 h3 h4 h5 h6 h7 h8 br b i strong em a pre code img tt
    div ins del sup sub p ol ul table thead tbody tfoot blockquote
    dl dt dd kbd q samp var hr ruby rt rp li tr td th s span strike
  </blockquote>
</div>

<hr>

GitLab uses GitLab Flavored Markdown (information about inline HTML can be found <a href="https://gitlab.com/gitlab-org/gitlab-ce/blob/master/doc/user/markdown.md#inline-html">here</a>) which uses the HTML::Pipeline SanitizationFilter.
More information about the filter can be found <a href="http://www.rubydoc.info/gems/html-pipeline/1.11.0/HTML/Pipeline/SanitizationFilter#WHITELIST-constant">here</a>.