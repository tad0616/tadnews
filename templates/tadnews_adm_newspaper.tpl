<div class="container-fluid">

    <{if $op=="creat_newspaper" or $op=="modify"}>
        <script type="text/javascript">
            //bkLib.onDomLoaded(function() { nicEditors.allTextAreas()});
            bkLib.onDomLoaded(function() {
                new nicEditor({fullPanel : true}).panelInstance('head');
                new nicEditor({fullPanel : true}).panelInstance('foot');
            });
        </script>

        <h1 class="my"><{$smarty.const._MA_TADNEWS_NP_STEP1}></h1>

        <form action="newspaper.php" method="post" id="myForm" role="form">
            <div class="form-group row mb-3">
                <label for="themes">
                <{$smarty.const._MA_TADNEWS_NP_THEMES}>
                </label>
                <{$nps_theme|default:''}>
            </div>

            <div class="form-group row mb-3">
                <label for="title"><{$smarty.const._MA_TADNEWS_NP_TITLE}></label>
                <input type="text" name="title" id="title" value="<{$np_title|default:''}>" class="form-control">
            </div>

            <div class="form-group row mb-3">
                <label for="head"><{$smarty.const._MA_TADNEWS_NP_CONTENT_HEAD}></label>
                <textarea name="head" id="head" class="form-control"><{$head|default:''}></textarea>
                <div class="alert alert-default"><{$smarty.const._MA_TADNEWS_NP_CONTENT_HEAD_DESC}></div>
            </div>

            <div class="form-group row mb-3">
                <label for="foot"><{$smarty.const._MA_TADNEWS_NP_CONTENT_FOOT}></label>
                <textarea name="foot" id="foot"  class="form-control"><{$foot|default:''}></textarea>
                <div class="alert alert-default"><{$smarty.const._MA_TADNEWS_NP_CONTENT_FOOT_DESC}></div>
            </div>

            <div class="text-center">
                <{$hidden|default:''}>
                <input type="hidden" name="op" value="save_newspaper_set">
                <{$XOOPS_TOKEN|default:''}>
                <button type="submit" class="btn btn-primary"><{$smarty.const._TADNEWS_NP_SUBMIT}></button>
            </div>
        </form>
    <{elseif $op=="add_newspaper"}>
        <h1 class="my"><{$smarty.const._MA_TADNEWS_NP_STEP2}></h1>
        <form action="newspaper.php" method="post" id="myForm" class="form-horizontal" role="form">
            <div class="form-group row mb-3">
                <div class="col-md-11">
                    <div class="alert alert-info">
                        <{$newspaper_set_title|default:''}>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-3">
                <{$tmt_box}>
            </div>

            <div class="text-center">
                <{$XOOPS_TOKEN|default:''}>
                <button type="submit" class="btn btn-primary"><{$smarty.const._TADNEWS_NP_SUBMIT}></button>
            </div>
        </form>
    <{elseif $op=="edit_newspaper"}>
        <h1 class="my"><{$smarty.const._MA_TADNEWS_NP_STEP3}></h1>
        <form action="newspaper.php" method="post" id="myForm" class="form-horizontal" role="form">
            <div class="form-group row mb-3">
                <label class="col-md-2 control-label col-form-label text-md-right text-md-end">
                    <{$smarty.const._MA_TADNEWS_NP_SUB_TITLE}>
                </label>
                <div class="col-md-10">
                    <input type="text" name="np_title" class="form-control" value="<{$np_title|default:''}>">
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-12">
                    <{$editor|default:''}>
                </div>
            </div>

            <div class="text-center">
                <input type="hidden" name="npsn" value="<{$npsn|default:''}>">
                <input type="hidden" name="op" value="save_all">
                <{$XOOPS_TOKEN|default:''}>
                <button type="submit" class="btn btn-primary"><{$smarty.const._TADNEWS_SUBMIT}></button>
            </div>
        </form>
    <{elseif $op=="sendmail"}>
        <h1 class="my"><{$smarty.const._MA_TADNEWS_NP_STEP4}></h1>

        <div class="alert alert-info">
        <{$total|default:''}>
        </div>

        <{if $log|default:false}>
            <form action="newspaper.php" method="post" id="myForm" class="form-horizontal" role="form">
                <table class="table table-sm table-condensed">
                <{assign var="i" value=0}>

                <{foreach from=$log item=data}>
                    <{if !$i}><tr><{/if}>
                    <td>
                        <label class="checkbox-inline">
                        <{$data.checkbox}><{$data.email}>
                        </label>
                    </td>
                    <td><{$data.data}></td>
                    <{assign var="i" value=$i+1}>
                    <{if $i == 2}></tr><{assign var="i" value=0}><{/if}>
                <{/foreach}>
                </table>
                <input type="hidden" name="op" value="send_now">
                <input type="hidden" name="npsn" value="<{$npsn|default:''}>">
                <div class="text-center">
                    <{$XOOPS_TOKEN|default:''}>
                <button type="submit" class="btn btn-primary"><{$smarty.const._MA_TADNEWS_SEND_NOW}></button>
                </div>
            </form>
        <{else}>
            <div class="alert alert-danger">
                <{$no_email|default:''}>
            </div>
        <{/if}>
        <iframe title="newspaper preview" src="newspaper.php?op=preview&npsn=<{$npsn|default:''}>" style="width: 100%; height: 480px;b order:1px solid gray; clear: both"><{$np_content|default:''}></iframe>
    <{elseif $op=="newspaper_email"}>
        <h1 class="my"><{$title|default:''}><{$smarty.const._MA_TADNEWS_NP_EMAIL}></h1>

        <div class="row">
            <div class="col-md-12">
                <a href="newspaper.php?nps_sn=<{$nps_sn|default:''}>" class="btn btn-info"><{$back|default:''}></a>
            </div>
        </div>

        <{if $log|default:false}>
            <div class="text-center">
                <{$bar|default:''}>
            </div>
            <table class="table table-striped">
                <{assign var="i" value=0}>

                <{foreach from=$log item=data}>
                <{if !$i}><tr><{/if}>
                    <{if $data.edit|default:false}>
                    <td colspan=5>
                        <form action="newspaper.php" method="post">
                            <input type="text" name="new_email" value="<{$data.email}>" style="width:100%;background-color:#FFFF99;color:black;"></td><td>
                            <input type="hidden" name="old_email" value="<{$data.email}>">
                            <input type="hidden" name="op" value="update_email">
                            <input type="hidden" name="nps_sn" value="<{$nps_sn|default:''}>">
                            <input type="hidden" name="g2p" value="<{$g2p|default:''}>">
                            <{$XOOPS_TOKEN|default:''}>
                            <input type="submit" value="<{$smarty.const._MA_TADNEWS_SAVE_CATE}>">
                        </form>
                    </td>
                    <{else}>
                    <td><{$data.email}></td>
                    <td>
                    <{if $data.ok=="ok"}>
                        <span class="badge badge-info"><{$data.ok}></span>
                    <{else}>
                        <span class="badge badge-important"><{$data.ok}></span>
                    <{/if}>
                    </td>
                    <td><{$data.order_date}></td>
                    <td>
                        <a href="newspaper.php?op=newspaper_email&nps_sn=<{$nps_sn|default:''}>&memail=<{$data.email}>&g2p=<{$g2p|default:''}>" class="btn btn-sm btn-xs btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}></a>
                        <a href="javascript:delete_tad_news_email_func('<{$data.email}>');" class="btn btn-sm btn-xs btn-danger"><i class="fa fa-times"></i> <{$smarty.const._TAD_DEL}></a>
                    </td>
                    <td>
                        <{$data.data}>
                    </td>
                    <{/if}>
                <{assign var="i" value=$i+1}>
                <{if $i == 2}></tr><{assign var="i" value=0}><{/if}>
                <{/foreach}>
            </table>
            <div class="text-center">
                <{$bar|default:''}>
            </div>
        <{/if}>
        <form action="newspaper.php" method="post">
            <textarea name="email_import" class="form-control" placeholder="<{$smarty.const._MA_TADNEWS_NP_EMAIL_IMPORT}>"></textarea>
            <input type="hidden" name="nps_sn" value="<{$nps_sn|default:''}>">
            <input type="hidden" name="op" value="email_import">
            <div class="text-center" style="margin: 20px 0px;">
                <{$XOOPS_TOKEN|default:''}>
                <button type="submit" class="btn btn-primary"><{$smarty.const._MA_TADNEWS_NP_EMAIL_IMPORT}></button>
            </div>
        </form>
    <{elseif $op=="sendmail_log"}>
        <h1 class="my"><{$title|default:''}></h1>

        <div class="row">
            <div class="col-md-12">
                <a href="newspaper.php?nps_sn=<{$nps_sn|default:''}>" class="btn btn-info"><{$back|default:''}></a>
            </div>
        </div>

        <{if $empty|default:false}>
            <div class="alert alert-danger">
                <{$smarty.const._MA_TADNEWS_EMPTY_LOG}>
            </div>
        <{else}>
            <table class="table table-striped">
                <{assign var="i" value=0}>
                <{foreach from=$log item=data}>
                <td><{$data.email}></td>
                <td><{$data.send_time}></td>
                <td><span class="badge badge-info"><{$data.log}></span></td>
                <{assign var="i" value=$i+1}>
                <{if $i == 2}></tr><{assign var="i" value=0}><{/if}>
                <{/foreach}>
            </table>
        <{/if}>
    <{else}>
        <h1 class="my"><{$smarty.const._MA_TADNEWS_NP_LIST}></h1>
        <div class="row" style="margin:10px;">
            <div class="col-md-5">
                <select name="nps_sn" id="nps_sn" class="form-select col-md-6" onChange="window.location.href='newspaper.php?nps_sn='+this.value ">
                <option value=""><{$smarty.const._MA_TADNEWS_NP_OPTION}></option>
                <{$option|default:''}>
                </select>
            </div>
            <div class="col-md-7">
                <{$create_btn|default:''}>
                <{$del_btn|default:''}>
                <{$edit_btn|default:''}>
            </div>
        </div>

        <{if $nps_sn|default:false}>
            <table class="table table-striped table-hover table-shadow">
                <tr>
                    <th><{$smarty.const._MA_TADNEWS_NP_TITLE}></th>
                    <th><{$smarty.const._MA_TADNEWS_NP_DATE}></th>
                    <th class="c"><{$smarty.const._MA_TADNEWS_FUNCTION}></th>
                </tr>

                <{foreach item=np from=$newspaper}>
                <tr>
                    <td>
                        <a href="../newspaper.php?op=preview&npsn=<{$np.allnpsn}>" target="_blank"><{$np.title}><{$np.number}></a>
                    </td>
                    <td><{$np.np_date}></td>
                    <td class="c">
                        <a href="newspaper.php?op=edit_newspaper&npsn=<{$np.allnpsn}>" class="btn btn-sm btn-xs btn-warning"><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}></a>
                        <a href="javascript:delete_tad_newspaper(<{$np.allnpsn}>);" class="btn btn-sm btn-xs btn-danger"><i class="fa fa-times"></i> <{$smarty.const._TAD_DEL}></a>
                        <a href="newspaper.php?op=sendmail_log&npsn=<{$np.allnpsn}>" class="btn btn-sm btn-xs btn-info"><i class="fa fa-eye"></i> <{$smarty.const._MA_TADNEWS_SEND_LOG}></a>
                        <a href="newspaper.php?op=sendmail&npsn=<{$np.allnpsn}>" class="btn btn-sm btn-xs btn-primary"><i class="fa fa-paper-plane"></i> <{$smarty.const._MA_TADNEWS_SEND_NOW}></a>
                    </td>
                </tr>
                <{/foreach}>
            </table>
        <{/if}>
    <{/if}>
</div>