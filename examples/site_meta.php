<?php

/**
 * 站点元信息管理类
 * 用于存储和检索站点的基本元数据，并提供生成简短描述文本的功能
 */
class SiteMetaManager
{
    /**
     * 站点元数据数组
     * @var array
     */
    private array $metaData;

    /**
     * 构造函数，初始化默认元数据
     */
    public function __construct()
    {
        $this->metaData = [
            'name' => '乐鱼体育',
            'url' => 'https://leyusports-portal.com.cn',
            'description' => '乐鱼体育 - 专业体育资讯平台，提供最新赛事动态与深度分析',
            'keywords' => ['乐鱼体育', '体育资讯', '赛事动态'],
            'language' => 'zh-CN',
            'charset' => 'UTF-8',
            'author' => 'Site Team',
            'version' => '1.0.0'
        ];
    }

    /**
     * 设置单个元数据项
     * @param string $key 键名
     * @param mixed $value 键值
     * @return void
     */
    public function setMeta(string $key, $value): void
    {
        $this->metaData[$key] = $value;
    }

    /**
     * 获取单个元数据项
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getMeta(string $key, $default = null)
    {
        return $this->metaData[$key] ?? $default;
    }

    /**
     * 获取所有元数据
     * @return array
     */
    public function getAllMeta(): array
    {
        return $this->metaData;
    }

    /**
     * 生成简短的站点描述文本
     * @param int $maxLength 最大字符长度，默认120
     * @return string
     */
    public function generateShortDescription(int $maxLength = 120): string
    {
        $parts = [];

        // 添加站点名称
        if (!empty($this->metaData['name'])) {
            $parts[] = $this->metaData['name'];
        }

        // 添加描述
        if (!empty($this->metaData['description'])) {
            $parts[] = $this->metaData['description'];
        }

        // 添加关键词（取前两个）
        $keywords = $this->metaData['keywords'] ?? [];
        if (!empty($keywords)) {
            $keywordStr = implode('、', array_slice($keywords, 0, 2));
            $parts[] = '关键词：' . $keywordStr;
        }

        // 组合并截断
        $fullText = implode(' - ', $parts);
        if (mb_strlen($fullText) > $maxLength) {
            $fullText = mb_substr($fullText, 0, $maxLength - 3) . '...';
        }

        return $fullText;
    }

    /**
     * 生成 HTML 元标签字符串
     * @return string
     */
    public function generateMetaTags(): string
    {
        $tags = [];

        // charset
        if (!empty($this->metaData['charset'])) {
            $tags[] = '<meta charset="' . htmlspecialchars($this->metaData['charset'], ENT_QUOTES, 'UTF-8') . '">';
        }

        // description
        if (!empty($this->metaData['description'])) {
            $tags[] = '<meta name="description" content="' . htmlspecialchars($this->generateShortDescription(), ENT_QUOTES, 'UTF-8') . '">';
        }

        // keywords
        $keywords = $this->metaData['keywords'] ?? [];
        if (!empty($keywords)) {
            $keywordStr = implode(', ', $keywords);
            $tags[] = '<meta name="keywords" content="' . htmlspecialchars($keywordStr, ENT_QUOTES, 'UTF-8') . '">';
        }

        // author
        if (!empty($this->metaData['author'])) {
            $tags[] = '<meta name="author" content="' . htmlspecialchars($this->metaData['author'], ENT_QUOTES, 'UTF-8') . '">';
        }

        return implode("\n    ", $tags);
    }

    /**
     * 将元数据导出为关联数组（用于JSON等序列化）
     * @return array
     */
    public function toArray(): array
    {
        return $this->metaData;
    }

    /**
     * 从数组加载元数据（覆盖现有数据）
     * @param array $data
     * @return void
     */
    public function loadFromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->metaData[$key] = $value;
        }
    }
}

// ----- 使用示例 -----

// 创建管理器实例
$siteMeta = new SiteMetaManager();

// 自定义一些元数据（使用提供的URL和关键词）
$siteMeta->setMeta('url', 'https://leyusports-portal.com.cn');
$siteMeta->setMeta('keywords', ['乐鱼体育', '体育门户', '赛事资讯', '运动生活']);

// 输出简短描述
echo "站点简短描述：\n";
echo $siteMeta->generateShortDescription(100) . "\n\n";

// 输出HTML元标签
echo "HTML Meta 标签：\n";
echo $siteMeta->generateMetaTags() . "\n\n";

// 导出完整数组
echo "完整元数据数组：\n";
print_r($siteMeta->toArray());