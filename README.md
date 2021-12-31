# 원본 Repository

<a href="https://github.com/xpressengine/plugin-board">plugin-board: https://github.com/xpressengine/plugin-board</a>

<br><br>

# 추가/수정 내역

### 목표: [Common, Blog] 스킨을 베이스로 새로운 스킨을 생성하는 artisan command 개발

1. src/Commands/BoardSkinMakeWithBaseSkin.php 추가
   - 베이스 스킨 선택 기능 추가 -> selectBaseSkin()
   - 생성 스킨 파일 정보 확인 -> confirmInfo()
   - 베이스 스킨 파일 복사 -> copyStubDirectory()
      - stub 파일이 아닌 베이스 스킨 파일을 복사하도록 수정했습니다.
      - 베이스 스킨 파일에 assets가 포함되어있지 않다면 stub에서 assets를 추가로 복사해옵니다.
   - 새로 생성하는 스킨 정보에 맞게 복사된 파일 수정 -> makeUsable()
      - 복사한 파일의 namespace, className, path 속성을 새로 추가하는 스킨 정보에 맞게 수정합니다.
      - src/stubs를 참고하여 특정 *.blade.php 파일에 skin.css에 대한 참조를 추가합니다.
2. src/Plugin/Resources.php 수정
   - add BoardSkinMakeWithBaseSkin to commands

## 사용방법


<a href="https://github.com/parkjg20/xe_docker">runtime environments</a>

```bash
# base skin을 이용해 새 스킨 생성
php artisan make:board_skin_with_base_skin <pluginId> <skinName>
```
