# A curl_multi Working Example - That Actually Works!!!

Both StackOverflow and PHP.net are filled with half-a$$ examples from more than 5 years ago,
some result with a long CPU hang-time, some simply do not work.. due to "reasons"...

THIS ONE WORKS
- provide an end result of an array,
- filled with all of the headers (in the orders appeared, including 30x redirections, broken down to key and value),
- the content of the page or resource (raw) - double as much urls you would like to use,

resources are cleaned properly and handlers are closed when possible to save memory.

<hr/>

Some keywords:
  CURLOPT_URL, CURLOPT_CONNECTTIMEOUT
, CURLOPT_TIMEOUT, CURLOPT_BUFFERSIZE 
, CURLOPT_HEADER, CURLINFO_HEADER_OUT 
, CURLOPT_RETURNTRANSFER, CURLOPT_FAILONERROR
, CURLOPT_SSL_VERIFYHOST, CURLOPT_SSL_VERIFYPEER
, CURLOPT_IPRESOLVE, CURLOPT_HTTP_VERSION
, CURLOPT_FOLLOWLOCATION, CURLOPT_MAXREDIRS
, CURLOPT_HTTPHEADER
, CURLMSG_DONE, CURLM_OK, CURLM_CALL_MULTI_PERFORM
, curl_multi_init, curl_multi_exec, curl_multi_getcontent
, curl_multi_remove_handle, curl_close, curl_multi_close
