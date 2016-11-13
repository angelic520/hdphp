<?php namespace app\system\controller;

class Component {
	//上传图片webuploader
	public function uploader() {
		$file = Upload::path( c( 'upload.path' ) . '/' . date( 'Y/m/d' ) )->make();
		if ( $file ) {
			$data = [
				'uid'        => v( 'user.info.uid' ) ?: v( 'user.member.uid' ),
				'name'       => $file[0]['name'],
				'filename'   => $file[0]['filename'],
				'path'       => $file[0]['path'],
				'extension'  => strtolower( $file[0]['ext'] ),
				'createtime' => time(),
				'size'       => $file[0]['size'],
				'data'       => Request::post( 'data', '' ),
				'data'       => Request::post( 'hash', '' )
			];
			Db::table( 'core_attachment' )->insert( $data );
			ajax( [ 'valid' => 1, 'message' => $file[0]['path'] ] );
		} else {
			ajax( [ 'valid' => 0, 'message' => \Upload::getError() ] );
		}
	}

	//获取文件列表webuploader
	public function filesLists() {
		$db   = Db::table( 'core_attachment' )->whereIn( 'extension', explode( ',', strtolower( $_GET['extensions'] ) ) )->orderBy( 'id', 'DESC' );
		$data = $db->get();
		if ( $data ) {
			foreach ( $data as $k => $v ) {
				$data[ $k ]['createtime'] = date( 'Y/m/d', $v['createtime'] );
				$data[ $k ]['size']       = Tool::getSize( $v['size'] );
			}
		}
		ajax( [ 'data' => $data ?: [ ], 'page' => $db->links() ] );
	}

	//删除图片delWebuploader
	public function removeImage() {
		$db   = Db::table( 'core_attachment' );
		$file = $db->where( 'id', $_POST['id'] )->first();
		if ( is_file( $file['path'] ) ) {
			unlink( $file['path'] );
		}
		$db->where( 'id', $_POST['id'] )->delete();
	}

	//百度编辑器
	public function ueditor() {
		$path   = ROOT_PATH . '/resource/hdjs/component/ueditor';
		$CONFIG = json_decode( preg_replace( "/\/\*[\s\S]+?\*\//", "", file_get_contents( $path . "/php/config.json" ) ), true );
		$action = $_GET['action'];
		switch ( $action ) {
			case 'config':
				$result = json_encode( $CONFIG );
				break;
			/* 上传图片 */
			case 'uploadimage':
				/* 上传涂鸦 */
			case 'uploadscrawl':
				/* 上传视频 */
			case 'uploadvideo':
				/* 上传文件 */
			case 'uploadfile':
				$result = include( $path . "/php/action_upload.php" );
				break;

			/* 列出图片 */
			case 'listimage':
				$result = include( $path . "/php/action_list.php" );
				break;
			/* 列出文件 */
			case 'listfile':
				$result = include( $path . "/php/action_list.php" );
				break;

			/* 抓取远程文件 */
			case 'catchimage':
				$result = include( $path . "/php/action_crawler.php" );
				break;

			default:
				$result = json_encode( [
					'state' => '请求地址出错'
				] );
				break;
		}
		/* 输出结果 */
		if ( isset( $_GET["callback"] ) ) {
			if ( preg_match( "/^[\w_]+$/", $_GET["callback"] ) ) {
				echo htmlspecialchars( $_GET["callback"] ) . '(' . $result . ')';
			} else {
				echo json_encode( [
					'state' => 'callback参数不合法'
				] );
			}
		} else {
			echo $result;
		}
	}
}